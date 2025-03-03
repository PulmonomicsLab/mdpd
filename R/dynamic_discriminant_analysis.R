args <- commandArgs(trailingOnly=TRUE)
assayType <- args[1]
path <- args[2]
method <- args[3]
alpha <- as.double(args[4])
p_adjust_method <- args[5]
filter_thres <- as.double(args[6])
taxa_level <- args[7]
threshold <- as.double(args[8])

inputPath <- "input/biom/"
libraryPath <- "Rlibs/"

.libPaths(c(libraryPath, .libPaths()))
library(phyloseq, quietly=TRUE)
library(microeco, quietly=TRUE)
library(file2meco, quietly=TRUE)

tryCatch (
    {
        bioprojects <- read.table(paste0(path, "bioprojects.tsv"), header=FALSE)[, 1]
        runs <- read.table(paste0(path, "runs.tsv"), header=FALSE)[, 1]

        # print(assayType)
        # print(bioprojects)
        # print(runs)

        # Initialize params
        if (assayType == "WMS") {
            tax_rank <- "Species"
            tax_prefix <- "s__"
            rel_abund <- 0.005
            freq <- 0.05
            pollution_filters = "Chordata"
        } else {
            tax_rank <- "Genus"
            tax_prefix <- "g__"
            rel_abund <- 0.0001
            freq <- 0.05
            pollution_filters = c("mitochondria", "chloroplast")
        }

        # Read biom
        subset_bioms = c()
        for (bioprojectID in bioprojects) {
            tryCatch(
                {
                    if (assayType == "WMS") {
                        # Read biom file
                        ps <- import_biom(paste0(inputPath, bioprojectID, "_", assayType, ".biom1"), parseFunction=parse_taxonomy_greengenes)
                        tax_table(ps) <- cbind(ps@tax_table, paste(ps@tax_table[, "Genus"], ps@tax_table[, "Species"], sep="_"))
                        colnames(ps@tax_table)[7] <- "Old_Species"
                        colnames(ps@tax_table)[8] <- "Species"

                        # Create phyloseq object to meco object
                        suppressMessages(meco_object <- phyloseq2meco(ps))
                        meco_object$tidy_dataset()
                        meco_object$tax_table <- meco_object$tax_table[, -7] # Remove Old_Species column
                    } else {
                        # Read biom RDS
                        ps <- readRDS(paste0(inputPath, bioprojectID, "_", assayType, "_ps_object.rds"))

                        # Create phyloseq object to meco object
                        ps@sam_data$Run <- rownames(ps@sam_data)
                        suppressMessages(meco_object <- phyloseq2meco(ps))
                        meco_object$tidy_dataset()
                    }
        #             print(ps)

                    # Filter pollution
                    suppressMessages(meco_object$filter_pollution(taxa = pollution_filters))
                    suppressMessages(meco_object$tidy_dataset())

                    # Filter based on abundance and detection
                    suppressMessages(meco_object$filter_taxa(rel_abund = rel_abund, freq = freq))
                    suppressMessages(meco_object$tidy_dataset())

                    # meco_object$sample_table <- subset(meco_object$sample_table, (Assay.Type == assayType) & (Isolation.source == isolationSource))
                    suppressMessages(meco_object$sample_table <- subset(meco_object$sample_table, (Run %in% runs)))
                    suppressMessages(meco_object$tidy_dataset())

                    # Revert to phyloseq object from meco object
                    ps <- meco2phyloseq(meco_object)
                    # Add ps to subset_bioms
                    subset_bioms <- c(subset_bioms, ps)
                }, error = function(condition) {
                }, warning = function(condition) {}
            )
        }

        # Merge bioms to generate total_biom
        total_biom <- do.call(merge_phyloseq, as.list(subset_bioms))
        # Add new column by merging SubGroup, IsolationSource, and BioProject
        total_biom@sam_data$SubGroup_IsolationSource_BioProject <- paste(total_biom@sam_data$SubGroup, total_biom@sam_data$IsolationSource, total_biom@sam_data$BioProject, sep="_")
        # Add new column by merging SubGroup and IsolationSource
        total_biom@sam_data$SubGroup_IsolationSource <- paste(total_biom@sam_data$SubGroup, total_biom@sam_data$IsolationSource, sep="_")
        # Create total_meco_object from total_biom
        suppressMessages(total_meco_object <- phyloseq2meco(total_biom))
        total_meco_object$tidy_dataset()

        suppressWarnings(
            suppressMessages(
                t1 <- trans_diff$new(
                    dataset = total_meco_object,
                    method = method,
                    alpha = alpha,
                    lefse_subgroup = NULL,
                    group = "SubGroup_IsolationSource_BioProject",
                    filter_thres = filter_thres,
                    taxa_level = taxa_level,
                    p_adjust_method = p_adjust_method
                )
            )
        )
        if (method == "lefse") {
            suppressWarnings(suppressMessages(p <- t1$plot_diff_bar(threshold = threshold, use_number = 1:200)))
            trim_lda <- p$data[, c("Taxa", "Group", "Value", "P.unadj", "P.adj", "Significance")]
        } else {
            suppressWarnings(suppressMessages(p <- t1$plot_diff_bar(use_number = 1:200)))
            trim_lda <- p$data[, c("Taxa", "Group", "logFC", "P.unadj", "P.adj", "Significance")]
            colnames(trim_lda) <- c("Taxa", "Group", "Value", "P.unadj", "P.adj", "Significance")
            trim_lda <- trim_lda[abs(trim_lda$Value) > threshold & (trim_lda$Taxa) != "s__" & (trim_lda$Taxa) != "g__" & (trim_lda$Taxa) != "f__" & (trim_lda$Taxa) != "o__", ]
        }

        p_adjust_json <- paste0("\"p_adjust\":\"", p_adjust_method, "\"")
        taxa_json <- "\"taxa\":["
        subgroup_json <- "\"subgroup\":["
        abundance_json <- "\"value\":["
        pval_json <- "\"pval\":["
        padj_json <- "\"padj\":["
        significance_json <- "\"significance\":["
        i <- 1
        while (i <= nrow(trim_lda)) {
            if (i == nrow(trim_lda)) {
                taxa_json <- paste0(taxa_json, "\"", trim_lda[i, "Taxa"], "\"")
                subgroup_json <- paste0(subgroup_json, "\"", trim_lda[i, "Group"], "\"")
                abundance_json <- paste0(abundance_json, trim_lda[i, "Value"])
                pval_json <- paste0(pval_json, trim_lda[i, "P.unadj"])
                padj_json <- paste0(padj_json, trim_lda[i, "P.adj"])
                significance_json <- paste0(significance_json, "\"", trim_lda[i, "Significance"], "\"")
            } else {
                taxa_json <- paste0(taxa_json, "\"", trim_lda[i, "Taxa"], "\",")
                subgroup_json <- paste0(subgroup_json, "\"", trim_lda[i, "Group"], "\",")
                abundance_json <- paste0(abundance_json, trim_lda[i, "Value"], ",")
                pval_json <- paste0(pval_json, trim_lda[i, "P.unadj"], ",")
                padj_json <- paste0(padj_json, trim_lda[i, "P.adj"], ",")
                significance_json <- paste0(significance_json, "\"", trim_lda[i, "Significance"], "\",")
            }
            i <- i + 1
        }
        taxa_json <- paste0(taxa_json, "]")
        subgroup_json <- paste0(subgroup_json, "]")
        abundance_json <- paste0(abundance_json, "]")
        pval_json <- paste0(pval_json, "]")
        padj_json <- paste0(padj_json, "]")
        significance_json <- paste0(significance_json, "]")
        lda_out_json <- paste0("{", p_adjust_json, ",", taxa_json, ",", subgroup_json, ",", abundance_json, ",", pval_json, ",", padj_json, ",", significance_json, "}")


        suppressWarnings(
            suppressMessages(
                t1 <- trans_diff$new(
                    dataset = total_meco_object,
                    method = method,
                    alpha = alpha,
                    lefse_subgroup = NULL,
                    group = "SubGroup_IsolationSource",
                    filter_thres = filter_thres,
                    taxa_level = taxa_level,
                    p_adjust_method = p_adjust_method
                )
            )
        )
        if (method == "lefse") {
            suppressWarnings(suppressMessages(p <- t1$plot_diff_bar(threshold = threshold, use_number = 1:200)))
            trim_lda <- p$data[, c("Taxa", "Group", "Value", "P.unadj", "P.adj", "Significance")]
        } else {
            suppressWarnings(suppressMessages(p <- t1$plot_diff_bar(use_number = 1:200)))
            trim_lda <- p$data[, c("Taxa", "Group", "logFC", "P.unadj", "P.adj", "Significance")]
            colnames(trim_lda) <- c("Taxa", "Group", "Value", "P.unadj", "P.adj", "Significance")
            trim_lda <- trim_lda[abs(trim_lda$Value) > threshold & (trim_lda$Taxa) != "s__" & (trim_lda$Taxa) != "g__" & (trim_lda$Taxa) != "f__" & (trim_lda$Taxa) != "o__", ]
        }

        p_adjust_json <- paste0("\"p_adjust\":\"", p_adjust_method, "\"")
        taxa_json <- "\"taxa\":["
        subgroup_json <- "\"subgroup\":["
        abundance_json <- "\"value\":["
        pval_json <- "\"pval\":["
        padj_json <- "\"padj\":["
        significance_json <- "\"significance\":["
        i <- 1
        while (i <= nrow(trim_lda)) {
            if (i == nrow(trim_lda)) {
                taxa_json <- paste0(taxa_json, "\"", trim_lda[i, "Taxa"], "\"")
                subgroup_json <- paste0(subgroup_json, "\"", trim_lda[i, "Group"], "\"")
                abundance_json <- paste0(abundance_json, trim_lda[i, "Value"])
                pval_json <- paste0(pval_json, trim_lda[i, "P.unadj"])
                padj_json <- paste0(padj_json, trim_lda[i, "P.adj"])
                significance_json <- paste0(significance_json, "\"", trim_lda[i, "Significance"], "\"")
            } else {
                taxa_json <- paste0(taxa_json, "\"", trim_lda[i, "Taxa"], "\",")
                subgroup_json <- paste0(subgroup_json, "\"", trim_lda[i, "Group"], "\",")
                abundance_json <- paste0(abundance_json, trim_lda[i, "Value"], ",")
                pval_json <- paste0(pval_json, trim_lda[i, "P.unadj"], ",")
                padj_json <- paste0(padj_json, trim_lda[i, "P.adj"], ",")
                significance_json <- paste0(significance_json, "\"", trim_lda[i, "Significance"], "\",")
            }
            i <- i + 1
        }
        taxa_json <- paste0(taxa_json, "]")
        subgroup_json <- paste0(subgroup_json, "]")
        abundance_json <- paste0(abundance_json, "]")
        pval_json <- paste0(pval_json, "]")
        padj_json <- paste0(padj_json, "]")
        significance_json <- paste0(significance_json, "]")
        merged_lda_out_json <- paste0("{", p_adjust_json, ",", taxa_json, ",", subgroup_json, ",", abundance_json, ",", pval_json, ",", padj_json, ",", significance_json, "}")


        out_json <- paste0("{\"lda\":", lda_out_json, ",\"merged_lda\":", merged_lda_out_json, "}")
        cat(out_json)
    },
    error = function(condition) {
        lda_out_json <- "{\"p_adjust\":\"\",\"taxa\":[],\"subgroup\":[],\"value\":[],\"pval\":[],\"padj\":[],\"significance\":[]}"
        merged_lda_out_json <- "{\"p_adjust\":\"\",\"taxa\":[],\"subgroup\":[],\"value\":[],\"pval\":[],\"padj\":[],\"significance\":[]}"
        out_json <- paste0("{\"lda\":", lda_out_json, ",\"merged_lda\":", merged_lda_out_json, "}")
        cat(out_json)
    }
)
