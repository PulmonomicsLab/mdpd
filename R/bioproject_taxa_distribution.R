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

# Initialize params
if (assayType == "WMS") {
    tax_rank <- "Species"
    tax_prefix <- "s__"
    rel_abund <- 0.005
    freq <- 0.05
    pollution_filters = "Chordata"
    plot_columns <- c("Taxonomy", "Run", "SubGroup", "Abundance")
} else {
    tax_rank <- "Genus"
    tax_prefix <- "g__"
    rel_abund <- 0.0001
    freq <- 0.05
    pollution_filters = c("mitochondria", "chloroplast")
    plot_columns <- c("Taxonomy", "Sample", "SubGroup", "Abundance")
}

# Read biom
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
    suppressMessages(meco_object <- phyloseq2meco(ps))
    meco_object$tidy_dataset()
}
# print(ps)

# Filter pollution
suppressMessages(meco_object$filter_pollution(taxa = pollution_filters))
meco_object$tidy_dataset()

# Filter based on abundance and detection
suppressMessages(meco_object$filter_taxa(rel_abund = rel_abund, freq = freq))
meco_object$tidy_dataset()

# meco_object$sample_table <- subset(meco_object$sample_table, (Assay.Type == assayType) & (Isolation.source == isolationSource))
meco_object$sample_table <- subset(meco_object$sample_table, (IsolationSource == isolationSource))
meco_object$tidy_dataset()

# Box plot Genus
suppressMessages(t1 <- trans_abund$new(dataset = meco_object, taxrank = tax_rank, ntaxa = 10))
p <- t1$plot_box(group = "SubGroup")
trim_taxa <- p$data[, plot_columns]
colnames(trim_taxa) <- c("Taxonomy", "Sample", "SubGroup", "Abundance")
trim_taxa <- trim_taxa[with(trim_taxa, order(-ave(trim_taxa$Abundance, trim_taxa$Taxonomy, FUN=median))),]

sample_json <- "\"sample\":["
taxa_json <- "\"taxa\":["
subgroup_json <- "\"subgroup\":["
abundance_json <- "\"abundances\":["
for (i in (1:nrow(trim_taxa))) {
	if (i == nrow(trim_taxa)) {
        sample_json <- paste0(sample_json, "\"", trim_taxa[i, "Sample"], "\"")
		taxa_json <- paste0(taxa_json, "\"", tax_prefix, trim_taxa[i, "Taxonomy"], "\"")
		subgroup_json <- paste0(subgroup_json, "\"", trim_taxa[i, "SubGroup"], "\"")
		abundance_json <- paste0(abundance_json, trim_taxa[i, "Abundance"])
	} else {
        sample_json <- paste0(sample_json, "\"", trim_taxa[i, "Sample"], "\",")
		taxa_json <- paste0(taxa_json, "\"", tax_prefix, trim_taxa[i, "Taxonomy"], "\",")
		subgroup_json <- paste0(subgroup_json, "\"", trim_taxa[i, "SubGroup"], "\",")
		abundance_json <- paste0(abundance_json, trim_taxa[i, "Abundance"], ",")
	}
}
sample_json <- paste0(sample_json, "]")
taxa_json <- paste0(taxa_json, "]")
subgroup_json <- paste0(subgroup_json, "]")
abundance_json <- paste0(abundance_json, "]")

out_json <- paste0("{", sample_json, ",", taxa_json, ",", subgroup_json, ",", abundance_json, "}")
cat(out_json)
