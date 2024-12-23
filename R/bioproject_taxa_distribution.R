args <- commandArgs(trailingOnly=TRUE)
bioprojectID <- args[1]
assayType <- args[2]
isolationSource <- args[3]

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

# meco_object$sample_table <- subset(meco_object$sample_table, (Assay.Type == assayType) & (Isolation.source == isolationSource))
meco_object$sample_table <- subset(meco_object$sample_table, (IsolationSource == isolationSource))
meco_object$tidy_dataset()

# Box plot Genus
suppressMessages(t1 <- trans_abund$new(dataset = meco_object, taxrank = "Genus", ntaxa = 10))
p <- t1$plot_box(group = "SubGroup")
trim_taxa <- p$data[, c("Taxonomy", "Sample", "SubGroup", "Abundance")]
trim_taxa <- trim_taxa[with(trim_taxa, order(-ave(trim_taxa$Abundance, trim_taxa$Taxonomy, FUN=median))),]

taxa_json <- "\"taxa\":["
subgroup_json <- "\"subgroup\":["
abundance_json <- "\"abundances\":["
for (i in (1:nrow(trim_taxa))) {
	if (i == nrow(trim_taxa)) {
		taxa_json <- paste0(taxa_json, "\"g_", trim_taxa[i, "Taxonomy"], "\"")
		subgroup_json <- paste0(subgroup_json, "\"", trim_taxa[i, "SubGroup"], "\"")
		abundance_json <- paste0(abundance_json, trim_taxa[i, "Abundance"])
	} else {
		taxa_json <- paste0(taxa_json, "\"g_", trim_taxa[i, "Taxonomy"], "\",")
		subgroup_json <- paste0(subgroup_json, "\"", trim_taxa[i, "SubGroup"], "\",")
		abundance_json <- paste0(abundance_json, trim_taxa[i, "Abundance"], ",")
	}
}
taxa_json <- paste0(taxa_json, "]")
subgroup_json <- paste0(subgroup_json, "]")
abundance_json <- paste0(abundance_json, "]")

out_json <- paste0("{", taxa_json, ",", subgroup_json, ",", abundance_json, "}")
cat(out_json)
