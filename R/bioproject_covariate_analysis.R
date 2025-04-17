args <- commandArgs(trailingOnly=TRUE)
bioprojectID <- args[1]
assayType <- args[2]
isolationSource <- args[3]
confounders <- strsplit(args[4], ",")[[1]]
path <- args[5]

set.seed(42)

inputPath <- "input/biom/"
libraryPath <- "Rlibs/"

.libPaths(c(libraryPath, .libPaths()))
library(phyloseq, quietly=TRUE)
library(microeco, quietly=TRUE)
library(file2meco, quietly=TRUE)
suppressMessages(library(dplyr, quietly=TRUE))

tryCatch(
    {
        # Initialize params
        if (assayType == "WMS") {
            use_data <- "Species"
            rel_abund <- 0.0001
            freq <- 0.05
            pollution_filters = "Chordata"
        } else {
            use_data <- "Genus"
            rel_abund <- 0.0001
            freq <- 0.05
            pollution_filters = c("mitochondria", "chloroplast")
        }

        # Read biom
        ps <- readRDS(paste0(inputPath, bioprojectID, "_", assayType, "_ps_object.rds"))

        # Create phyloseq object to meco object
        suppressMessages(meco_object <- phyloseq2meco(ps))
        meco_object$tidy_dataset()
        # print(ps)


        # Filter pollution
        suppressMessages(meco_object$filter_pollution(taxa = pollution_filters))
        meco_object$tidy_dataset()

        # Filter based on abundance and detection
        suppressMessages(meco_object$filter_taxa(rel_abund = rel_abund, freq = freq))

        meco_object$sample_table <- subset(meco_object$sample_table, (IsolationSource == isolationSource))
        meco_object$tidy_dataset()

        subgroups <- unique(meco_object$sample_table$SubGroup)

        effects <- c()

        if ("Gender" %in% confounders) {
            for(sg in subgroups) {
                genders <- unique(meco_object$sample_table[meco_object$sample_table$SubGroup == sg, "Gender"])
                for(gender in genders) {
                    new_col <- c()
                    for(i in 1:nrow(meco_object$sample_table)) {
                        if (meco_object$sample_table[i, "SubGroup"] == sg & meco_object$sample_table[i, "Gender"] == gender)
                            new_col <- c(new_col, "Yes")
                        else
                            new_col <- c(new_col, "No")
                    }
                    meco_object$sample_table[, ncol(meco_object$sample_table) + 1] <- new_col
                    new_col_name <- paste0(sg, "_", gender)
                    new_col_name <- gsub(" ", "_", new_col_name)
                    new_col_name <- gsub("-", "_", new_col_name)
                    new_col_name <- gsub("\\(", "", new_col_name)
                    new_col_name <- gsub("\\[", "", new_col_name)
                    new_col_name <- gsub("\\)", "", new_col_name)
                    new_col_name <- gsub("\\]", "", new_col_name)
                    colnames(meco_object$sample_table)[ncol(meco_object$sample_table)] <- new_col_name
                    effects <- c(effects, new_col_name)
                }
            }
        }

        if ("AgeGroup" %in% confounders) {
            meco_object$sample_table$Age <- as.numeric(meco_object$sample_table$Age)
            meco_object$sample_table <- meco_object$sample_table %>% mutate(AgeGroup = cut(Age, breaks=c(0,1,11,21,31,41,51,61,71,81,91,101,111)))
            for(sg in subgroups) {
                agegroups <- unique(meco_object$sample_table[meco_object$sample_table$SubGroup == sg, "AgeGroup"])
                for(ag in agegroups) {
                    new_col <- c()
                    for(i in 1:nrow(meco_object$sample_table)) {
                        if (meco_object$sample_table[i, "SubGroup"] == sg & meco_object$sample_table[i, "AgeGroup"] == ag)
                            new_col <- c(new_col, "Yes")
                        else
                            new_col <- c(new_col, "No")
                    }
                    meco_object$sample_table[, ncol(meco_object$sample_table) + 1] <- new_col
                    new_col_name <- paste0(sg, "_", ag)
                    new_col_name <- gsub(" ", "_", new_col_name)
                    new_col_name <- gsub("-", "_", new_col_name)
                    new_col_name <- gsub("\\(", "", new_col_name)
                    new_col_name <- gsub("\\[", "", new_col_name)
                    new_col_name <- gsub(",", "_to_", new_col_name)
                    new_col_name <- gsub("\\]", "", new_col_name)
                    new_col_name <- gsub("\\)", "", new_col_name)
                    colnames(meco_object$sample_table)[ncol(meco_object$sample_table)] <- new_col_name
                    effects <- c(effects, new_col_name)
                }
            }
        }

        if ("SmokingStatus" %in% confounders) {
            for(sg in subgroups) {
                smokinggroups <- unique(meco_object$sample_table[meco_object$sample_table$SubGroup == sg, "SmokingStatus"])
                for(smoking in smokinggroups) {
                    new_col <- c()
                    for(i in 1:nrow(meco_object$sample_table)) {
                        if (meco_object$sample_table[i, "SubGroup"] == sg & meco_object$sample_table[i, "SmokingStatus"] == smoking)
                            new_col <- c(new_col, "Yes")
                        else
                            new_col <- c(new_col, "No")
                    }
                    meco_object$sample_table[, ncol(meco_object$sample_table) + 1] <- new_col
                    new_col_name <- paste0(sg, "_", smoking)
                    new_col_name <- gsub(" ", "_", new_col_name)
                    new_col_name <- gsub("-", "_", new_col_name)
                    new_col_name <- gsub("\\(", "", new_col_name)
                    new_col_name <- gsub("\\[", "", new_col_name)
                    new_col_name <- gsub("\\)", "", new_col_name)
                    new_col_name <- gsub("\\]", "", new_col_name)
                    colnames(meco_object$sample_table)[ncol(meco_object$sample_table)] <- new_col_name
                    effects <- c(effects, new_col_name)
                }
            }
        }
        # print(effects)

        t4 <- trans_env$new(dataset = meco_object, env_cols = 1:ncol(meco_object$sample_table))

        # Parameter passing to add parameters of Maaslin2 function
        # if ("SmokingStatus" %in% effects & length(unique(meco_object$sample_table$SmokingStatus))) {
        #     ref <- c("SmokingStatus,Non Smoker")
        # } else {
        #     ref <- "NONE"
        # }

        # Perform covariate analysis
        suppressWarnings(suppressMessages(
            t4$cal_cor(
                use_data = use_data,
                use_taxa_num = nrow(meco_object$otu_table),
                cor_method = "maaslin2",
                standardize = FALSE,
                transform = "LOG",
                fixed_effects = effects, #c("AgeGroup", "Gender", SmokingStatus),
                random_effects = "NONE",
                normalization = "TSS",
                reference = "NONE",
                plot_heatmap = FALSE,
                plot_scatter = FALSE,
                p_adjust_method = "fdr",
                tmp_input_maaslin2 = path,
                tmp_output_maaslin2 = path
            )
        ))

        if (length(unique(t4$res_cor$Env)) < 2) {
            heatmap <- t4$res_cor[, c("Taxa", "Env", "coef", "Pvalue", "AdjPvalue", "Significance")]
            colnames(heatmap) <- c("Taxa", "Name", "Value", "Pvalue", "AdjPvalue", "Significance")
            heatmap$Name <- lapply(heatmap$Name, as.character)
            for (i in 1:nrow(heatmap)) {
                taxa_splits <- strsplit(heatmap[i, "Taxa"], "\\.")[[1]]
                heatmap[i, "Taxa"] <- taxa_splits[length(taxa_splits)]
                heatmap[i, "Name"] <- paste0(heatmap[i, "Name"][1], "Yes")
            }
        } else {
            p <- t4$plot_cor(keep_prefix = TRUE)
            heatmap <- p$data[, c("Taxa", "name", "coef", "Pvalue", "AdjPvalue", "Significance")]
            colnames(heatmap) <- c("Taxa", "Name", "Value", "Pvalue", "AdjPvalue", "Significance")
        }
        # print(heatmap)

        taxa_json <- "\"taxa\":["
        name_json <- "\"name\":["
        value_json <- "\"value\":["
        p_value_json <- "\"p_value\":["
        adj_p_value_json <- "\"adj_p_value\":["
        significance_json <- "\"significance\":["
        i <- 1
        while (i <= nrow(heatmap)) {
            if (i == nrow(heatmap)) {
                taxa_json <- paste0(taxa_json, "\"", heatmap[i, "Taxa"], "\"")
                name_json <- paste0(name_json, "\"", substr(heatmap[i, "Name"], 1, nchar(heatmap[i, "Name"]) - 3), "\"")
                value_json <- paste0(value_json, heatmap[i, "Value"])
                p_value_json <- paste0(p_value_json, heatmap[i, "Pvalue"])
                adj_p_value_json <- paste0(adj_p_value_json, heatmap[i, "AdjPvalue"])
                significance_json <- paste0(significance_json, "\"", heatmap[i, "Significance"], "\"")
            } else {
                taxa_json <- paste0(taxa_json, "\"", heatmap[i, "Taxa"], "\",")
                name_json <- paste0(name_json, "\"", substr(heatmap[i, "Name"], 1, nchar(heatmap[i, "Name"]) - 3), "\",")
                value_json <- paste0(value_json, heatmap[i, "Value"], ",")
                p_value_json <- paste0(p_value_json, heatmap[i, "Pvalue"], ",")
                adj_p_value_json <- paste0(adj_p_value_json, heatmap[i, "AdjPvalue"], ",")
                significance_json <- paste0(significance_json, "\"", heatmap[i, "Significance"], "\",")
            }
            i <- i + 1
        }
        taxa_json <- paste0(taxa_json, "]")
        name_json <- paste0(name_json, "]")
        value_json <- paste0(value_json, "]")
        p_value_json <- paste0(p_value_json, "]")
        adj_p_value_json <- paste0(adj_p_value_json, "]")
        significance_json <- paste0(significance_json, "]")

        out_json <- paste0("{", taxa_json, ",", name_json, ",", value_json, ",", p_value_json, ",", adj_p_value_json, ",", significance_json, "}")
        cat(out_json)
    },
    error = function(condition) {
        out_json <- "{\"taxa\":[],\"name\":[],\"value\":[],\"p_value\":[],\"adj_p_value\":[],\"significance\":[]}"
        cat(out_json)
    }
)
