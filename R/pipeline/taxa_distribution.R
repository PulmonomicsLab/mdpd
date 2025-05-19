#This Rscript allows to get the abundances of the microbes across different subgroups and human body sites

setwd("")

library(phyloseq)
library(microeco)
library(file2meco)
library(stringr)

#choose assay types one at a time

# at <- "WMS"
at <- "Amplicon-16S"
# at <- "Amplicon-ITS"

#For WMS
if (at == "WMS") {
  tax_rank <- "Species"
  tax_prefix <- "s__"
  rel_abund <- 0.0001
  freq <- 0.05
  pollution_filters = c("Chordata")
} else {
  tax_rank <- "Genus"
  tax_prefix <- "g__"
  rel_abund <- 0.0001
  freq <- 0.05
  pollution_filters = c("mitochondria", "chloroplast")
}

file_names <- list.files()
biom_file_names <- c()
for (f in file_names) {
  if (at == "WMS") {
    if (substring(f, nchar(f)-16) == "WMS_ps_object.rds")
      biom_file_names <- c(biom_file_names, f)
  } else if (at =="Amplicon-16S") {
    if (substring(f, nchar(f)-25) == "Amplicon-16S_ps_object.rds")
      biom_file_names <- c(biom_file_names, f)
  } else if (at == "Amplicon-ITS") {
    if (substring(f, nchar(f)-25) == "Amplicon-ITS_ps_object.rds")
      biom_file_names <- c(biom_file_names, f)
  }
}
print(biom_file_names)
rm(file_names, f)

taxa <- c()
samples <- c()
abundances <- c()
sds <- c()
ses <- c()
subgroups <- c()
bioprojects <- c()
for (i in 1:length(biom_file_names)) {
  tryCatch(
    {
      print(paste0("Working on (", i, "/", length(biom_file_names), ")"))
      ps <- readRDS(biom_file_names[i])
      # ps <- import_biom(WMS_file_names[i], parseFunction = parse_taxonomy_greengenes)
      
      # Create phyloseq object to meco object
      meco_object <- phyloseq2meco(ps)
      meco_object$tidy_dataset()
      
      # Filter pollution
      suppressMessages(meco_object$filter_pollution(taxa = pollution_filters))
      meco_object$tidy_dataset()

      suppressMessages(meco_object$filter_taxa(rel_abund = rel_abund, freq = freq))
      meco_object$tidy_dataset()
      
      meco_object$sample_table$SubGroup_BioProject <- paste(meco_object$sample_table$SubGroup, meco_object$sample_table$BioProject, sep="_")
      
      # Bar plot Genus
      t1 <- trans_abund$new(dataset = meco_object, taxrank = tax_rank, ntaxa = NULL, groupmean="SubGroup_BioProject")
      t1$data_abund[c("SubGroup", "BioProject")] <- str_split_fixed(t1$data_abund$Sample, "_", 2)
      taxa <- c(taxa, as.character(t1$data_abund$Taxonomy))
      samples <- c(samples, t1$data_abund$Sample)
      abundances <- c(abundances, t1$data_abund$Abundance)
      sds <- c(sds, t1$data_abund$SD)
      ses <- c(ses, t1$data_abund$SE)
      subgroups <- c(subgroups, t1$data_abund$SubGroup)
      bioprojects <- c(bioprojects, t1$data_abund$BioProject)
      # if (i == 5)
      #   break
    }, error = function(condition) {
    }, warning = function(condition) {}
  )
}

taxa_distribution <- data.frame(
  Taxonomy=taxa,
  Sample=samples,
  Abundance=abundances,
  SD=sds,
  SE=ses,
  Subgroup=subgroups,
  BioProject=bioprojects
)
taxa_distribution <- taxa_distribution[taxa_distribution$Taxonomy != "unidentified", ]

write.csv(taxa_distribution, file=paste0("taxa_distribution_", at, ".tsv"), sep="\t")
