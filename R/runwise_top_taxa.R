args <- commandArgs(trailingOnly=TRUE)
runID <- args[1]
bioprojectID <- args[2]

inputPath <- "input/biom/"
libraryPath <- "Rlibs/"

.libPaths(c(libraryPath, .libPaths()))
library(phyloseq, quietly=TRUE)
library(microeco, quietly=TRUE)
library(file2meco, quietly=TRUE)

# Read biom RDS
ps <- readRDS(paste0(inputPath, bioprojectID, "_ps_object.rds"))
# print(ps)

# Create phyloseq object to meco object
meco_object <- phyloseq2meco(ps)
meco_object$tidy_dataset()

# Filter pollution
suppressMessages(meco_object$filter_pollution(taxa = c("mitochondria", "chloroplast")))
meco_object$tidy_dataset()

# Filter based on abundance and detection
suppressMessages(meco_object$filter_taxa(rel_abund = 0.0001, freq = 0.05))
meco_object$tidy_dataset()

meco_object$sample_table <- subset(meco_object$sample_table, Sample == runID)
meco_object$tidy_dataset()

# Bar plot Genus
suppressMessages(t3 <- trans_abund$new(dataset = meco_object, taxrank = "Genus", ntaxa = 50))
trim_taxa <- t3$data_abund[, c("Taxonomy", "Sample", "Abundance")]
ordered_taxa <- trim_taxa[order(trim_taxa$Abundance, decreasing=TRUE),]
# print(ordered_taxa)

taxa_json <- "\"taxa\":["
abundance_json <- "\"abundances\":["
if (nrow(ordered_taxa) < 10) {
	end <- nrow(ordered_taxa)
} else {
	end <- 10
}
for (i in (1:end)) {
	if (i == end) {
		taxa_json <- paste0(taxa_json, "\"g_", ordered_taxa[i, "Taxonomy"], "\"")
		abundance_json <- paste0(abundance_json, ordered_taxa[i, "Abundance"])
	} else {
		taxa_json <- paste0(taxa_json, "\"g_", ordered_taxa[i, "Taxonomy"], "\",")
		abundance_json <- paste0(abundance_json, ordered_taxa[i, "Abundance"], ",")
	}
}
taxa_json <- paste0(taxa_json, "]")
abundance_json <- paste0(abundance_json, "]")

out_json <- paste0("{", taxa_json, ",", abundance_json, "}")
cat(out_json)
