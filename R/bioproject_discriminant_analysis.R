args <- commandArgs(trailingOnly=TRUE)
bioprojectID <- args[1]
assayType <- args[2]
isolationSource <- args[3]
method <- args[4]
alpha <- as.double(args[5])
p_adjust_method <- args[6]
filter_thres <- as.double(args[7])
taxa_level <- args[8]
threshold <- as.double(args[9])

set.seed(42)

inputPath <- "input/biom/"
libraryPath <- "Rlibs/"

.libPaths(c(libraryPath, .libPaths()))
library(phyloseq, quietly=TRUE)
library(microeco, quietly=TRUE)
library(file2meco, quietly=TRUE)

tryCatch(
    {
        # Initialize params
        if (assayType == "WMS") {
            rel_abund <- 0.0001
            freq <- 0.05
            pollution_filters = "Chordata"
        } else {
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
        meco_object$tidy_dataset()

        meco_object$sample_table <- subset(meco_object$sample_table, (IsolationSource == isolationSource))
        meco_object$tidy_dataset()

        # LDA plot Genus
        suppressWarnings(suppressMessages(t1 <- trans_diff$new(dataset = meco_object, method = method, alpha = alpha, lefse_subgroup = NULL, group = "SubGroup", filter_thres = filter_thres, taxa_level = taxa_level, p_adjust_method = p_adjust_method)))
        if (method == "lefse") {
            p <- t1$plot_diff_bar(threshold = threshold, use_number = 1:200)
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

        out_json <- paste0("{", p_adjust_json, ",", taxa_json, ",", subgroup_json, ",", abundance_json, ",", pval_json, ",", padj_json, ",", significance_json, "}")
        cat(out_json)
    },
    error = function(condition) {
        out_json <- "{\"p_adjust\":\"\",\"taxa\":[],\"subgroup\":[],\"value\":[],\"pval\":[],\"padj\":[],\"significance\":[]}"
        cat(out_json)
    }
)
