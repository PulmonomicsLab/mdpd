args <- commandArgs(trailingOnly=TRUE)
bioprojectID <- args[1]
assayType <- args[2]
isolationSource <- args[3]
method <- args[4]
alpha <- args[5]
p_adjust_method <- args[6]
filter_thres <- args[7]
taxa_level <- args[8]
threshold <- args[9]

set.seed(42)

inputPath <- "input/biom/"
libraryPath <- "Rlibs/"

.libPaths(c(libraryPath, .libPaths()))
library(phyloseq, quietly=TRUE)
library(microeco, quietly=TRUE)
library(file2meco, quietly=TRUE)

# Read biom RDS
ps <- readRDS(paste0(inputPath, bioprojectID, "_", assayType, "_ps_object.rds"))
# print(ps)

# Create phyloseq object to meco object
meco_object <- suppressMessages(phyloseq2meco(ps))
meco_object$tidy_dataset()

# Filter pollution
suppressMessages(meco_object$filter_pollution(taxa = c("mitochondria", "chloroplast")))
meco_object$tidy_dataset()

# Filter based on abundance and detection
suppressMessages(meco_object$filter_taxa(rel_abund = 0.0001, freq = 0.05))
meco_object$tidy_dataset()

meco_object$sample_table <- subset(meco_object$sample_table, (IsolationSource == isolationSource))
meco_object$tidy_dataset()

# LDA plot Genus
tryCatch(
	{
		suppressWarnings(suppressMessages(t1 <- trans_diff$new(dataset = meco_object, method = method, alpha = alpha, lefse_subgroup = NULL, group = "SubGroup", filter_thres = filter_thres, taxa_level = taxa_level, p_adjust_method = p_adjust_method)))
		if (method == "lefse") {
			p <- t1$plot_diff_bar(threshold = threshold, use_number = 1:200)
			trim_lda <- p$data[, c("Taxa", "Group", "Value")]
		} else {
			suppressWarnings(suppressMessages(p <- t1$plot_diff_bar(use_number = 1:200)))
			trim_lda <- p$data[, c("Taxa", "Group", "logFC")]
			colnames(trim_lda) <- c("Taxa", "Group", "Value")
			trim_lda <- trim_lda[abs(trim_lda$Value) > threshold & (trim_lda$Taxa) != "g__" & (trim_lda$Taxa) != "f__" & (trim_lda$Taxa) != "o__", ]
		}

		taxa_json <- "\"taxa\":["
		subgroup_json <- "\"subgroup\":["
		abundance_json <- "\"value\":["
		i <- 1
		while (i <= nrow(trim_lda)) {
			if (i == nrow(trim_lda)) {
				taxa_json <- paste0(taxa_json, "\"", trim_lda[i, "Taxa"], "\"")
				subgroup_json <- paste0(subgroup_json, "\"", trim_lda[i, "Group"], "\"")
				abundance_json <- paste0(abundance_json, trim_lda[i, "Value"])
			} else {
				taxa_json <- paste0(taxa_json, "\"", trim_lda[i, "Taxa"], "\",")
				subgroup_json <- paste0(subgroup_json, "\"", trim_lda[i, "Group"], "\",")
				abundance_json <- paste0(abundance_json, trim_lda[i, "Value"], ",")
			}
			i <- i + 1
		}
		taxa_json <- paste0(taxa_json, "]")
		subgroup_json <- paste0(subgroup_json, "]")
		abundance_json <- paste0(abundance_json, "]")

		out_json <- paste0("{", taxa_json, ",", subgroup_json, ",", abundance_json, "}")
		cat(out_json)
	},
	error = function(condition) {
		out_json <- "{\"taxa\":[],\"subgroup\":[],\"value\":[]}"
		cat(out_json)
	}
)
