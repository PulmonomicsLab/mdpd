#This Rscript allows to upadate the metadata of the .biom files
# args=commandArgs(trailingOnly=TRUE)
bioproject_file_path_prefix <- "/input_path/"
input_path_prefix <- "/input_path/"
output_path_prefix <- "/output_path/"

#list of bioprojects
bioproject_file <- read.csv("list of bioprojects.csv", header=FALSE)
bioprojects <- bioproject_file[, 1]
#columns <- bioproject_file[, 2]
print(paste0("Number of BioProjects = ", length(bioprojects)))
print("List of BioProjects = ")
print(bioprojects)
run_table <- read.csv("input_run_table.csv", row.names = 1)

library(phyloseq)

options(lifecycle_verbosity = "quiet")

execCount <- 0
for (bioproject in bioprojects) {
  execCount <- execCount + 1
  print(paste0("Executing for the BioProject: ", bioproject, " (", execCount, "/", length(bioprojects), ")"))
  input_path <- paste0(input_path_prefix)
  output_path <- paste0(output_path_prefix)
  
  metadata <- subset(run_table, BioProject == bioproject)
  #for WMS
  ps <- import_biom(paste0(input_path, bioproject, "_WMS", ".biom1"),parseFunction=parse_taxonomy_greengenes)
  print(ps)
  tax_table(ps) <- cbind(ps@tax_table, paste(ps@tax_table[, "Genus"], ps@tax_table[, "Species"], sep="_"))
  colnames(ps@tax_table)[7] <- "Old_Species"
  colnames(ps@tax_table)[8] <- "Species"
  tax_table(ps) <- ps@tax_table[, -7]
  rownames(ps@sam_data) <- ps@sam_data$Run
  colnames(ps@otu_table) <- ps@sam_data$Run
  ps@sam_data <- ps@sam_data[,-1]
  #for amplicon-16S
  ps <- readRDS(paste0(input_path, bioproject, "_Amplicon-16S_ps_object", ".rds"))
   #for amplicon-ITS
  ps <- readRDS(paste0(input_path, bioproject, "_Amplicon-ITS_ps_object", ".rds"))
  
  sample_data(ps) <- as.data.frame(metadata)
  #for wms
  saveRDS(ps, paste0(output_path, bioproject, "_WMS.rds"))
  #for amplicon
  saveRDS(ps, paste0(output_path, bioproject, "_Amplicon-16S_ps_object.rds", / "_Amplicon-ITS_ps_object.rds"))
  print(paste0("Done for the BioProject: ", bioproject))
}

# Checking
file_names <- list.files(output_path_prefix)
biom_file_names <- c()
for (f in file_names)
  biom_file_names <- c(biom_file_names, f)
print(biom_file_names)
rm(file_names, f)

n_runs <- 0
for (i in 1:length(biom_file_names)) {
  ps <- readRDS(paste0(output_path_prefix, biom_file_names[i]))
  # print(colnames(ps@sam_data))
  # print(biom_file_names[i])
  print(paste0("Running (", i, "/", length(biom_file_names), ") - ", "BioProject: ", ps@sam_data[1, "BioProject"], ", Assay type: ", ps@sam_data[1, "AssayType"]))
  print(paste0("    Runs: ", nrow(ps@sam_data)))
  n_runs <- n_runs + nrow(ps@sam_data)
}
print(n_runs)
