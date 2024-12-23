args <- commandArgs(trailingOnly=TRUE)
bioprojectID <- args[1]
assayType <- args[2]
effects <- strsplit(args[3], ",")[[1]]
path <- args[4]

set.seed(42)

inputPath <- "input/biom/"
libraryPath <- "Rlibs/"

.libPaths(c(libraryPath, .libPaths()))
library(phyloseq, quietly=TRUE)
library(microeco, quietly=TRUE)
library(file2meco, quietly=TRUE)
suppressMessages(library(dplyr, quietly=TRUE))

# Read biom RDS
ps <- readRDS(paste0(inputPath, bioprojectID, "_", assayType, "_ps_object.rds"))
# print(ps)

# Create phyloseq object to meco object
suppressMessages(meco_object <- phyloseq2meco(ps))
meco_object$tidy_dataset()

# Filter pollution
suppressMessages(meco_object$filter_pollution(taxa = c("mitochondria", "chloroplast")))
meco_object$tidy_dataset()

# Filter based on abundance and detection
suppressMessages(meco_object$filter_taxa(rel_abund = 0.0001, freq = 0.05))
meco_object$tidy_dataset()

meco_object$sample_table <- meco_object$sample_table %>% mutate(AgeGroup = cut(Age, breaks=c(0,1,11,21,31,41,51,61,71,81,91,101,111)))

t4 <- trans_env$new(dataset = meco_object, env_cols = 1:ncol(meco_object$sample_table))

# Parameter passing to add parameters of Maaslin2 function
if ("SmokingStatus" %in% effects & length(unique(meco_object$sample_table$SmokingStatus))) {
    ref <- c("SmokingStatus,Non Smoker")
} else {
    ref <- "NONE"
}

print(effects)
print(ref)

# Perform covariate analysis
suppressWarnings(suppressMessages(
    t4$cal_cor(
        use_data = "Genus",
        use_taxa_num = nrow(meco_object$otu_table),
        cor_method = "maaslin2",
        standardize = FALSE,
        transform = "LOG",
        fixed_effects = effects, #c("AgeGroup", "Gender"),
        random_effects = "NONE",
        normalization = "TSS",
        reference = ref, #"NONE",
        plot_heatmap = FALSE,
        plot_scatter = FALSE,
        p_adjust_method = "fdr",
        tmp_input_maaslin2 = path,
        tmp_output_maaslin2 = path
    )
))
p <- t4$plot_cor(keep_prefix = TRUE)
heatmap <- p$data

heatmap <- heatmap[, c("Taxa", "name", "coef", "Pvalue", "AdjPvalue", "Significance")]
colnames(heatmap) <- c("Taxa", "Name", "Value", "Pvalue", "AdjPvalue", "Significance")
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
        name_json <- paste0(name_json, "\"", heatmap[i, "Name"], "\"")
        value_json <- paste0(value_json, heatmap[i, "Value"])
        p_value_json <- paste0(p_value_json, heatmap[i, "Pvalue"])
        adj_p_value_json <- paste0(adj_p_value_json, heatmap[i, "AdjPvalue"])
        significance_json <- paste0(significance_json, "\"", heatmap[i, "Significance"], "\"")
    } else {
        taxa_json <- paste0(taxa_json, "\"", heatmap[i, "Taxa"], "\",")
        name_json <- paste0(name_json, "\"", heatmap[i, "Name"], "\",")
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
