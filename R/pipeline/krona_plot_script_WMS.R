#This Rscript allows to get the Microbial composition (krona Plot) using psadd package and kronatools
#for WMS
args=commandArgs(trailingOnly=TRUE)
bioproject_file_path_prefix <- "/input_path/"
input_path_prefix <- "/input_path/"
output_path_prefix <- "output_path/"

#get the bioproject list
bioproject_list_file_name <- paste0(bioproject_file_path_prefix, args[1])
bioproject_file <- read.csv("list_of_bioprojects", header=FALSE)
bioprojects <- bioproject_file[, 1]
print(paste0("Number of BioProjects = ", length(bioprojects)))
print("List of BioProjects = ")
print(bioprojects)

library(psadd)
library(phyloseq)

options(lifecycle_verbosity = "quiet")

execCount <- 0
for (bioproject in bioprojects) {
  execCount <- execCount + 1
  print(paste0("Executing for the BioProject: ", bioproject, " (", execCount, "/", length(bioprojects), ")"))
  input_path <- paste0(input_path_prefix)
  output_path <- paste0(output_path_prefix)

  ps <- readRDS(paste0(input_path, bioproject, "_WMS_ps_object.rds"))
  ps = subset_taxa(ps, Phylum != "Chordata")
  print(ps)
  isolationSources <- unique(ps@sam_data$IsolationSource)
  print(isolationSources)

  # Krona Plot Subgroup
  for (isolationSource in isolationSources) {
   #read phyloseq object
    ps <- readRDS(paste0(input_path, bioproject, "_WMS_ps_object.rds"))
    ps = subset_taxa(ps, Phylum != "Chordata")
    ps <- subset_samples(ps, IsolationSource == isolationSource)
    for(i in (1:nrow(ps@sam_data))) {
      ps@sam_data[i, "SubGroup"] <- gsub(" ", "_", ps@sam_data[i, "SubGroup"])
      ps@sam_data[i, "SubGroup"] <- gsub("/", "_", ps@sam_data[i, "SubGroup"])
    }
#     ps@sam_data <- subset(ps@sam_data, (IsolationSource == isolationSource))
#     print(ps)
    plot_krona(ps, paste0(output_path, bioproject, "_WMS_", gsub(" ", "_", isolationSource), "_krona_subgroup"), "SubGroup", trim = T)
  }

  # Krona Plot Runwise
  ps <- readRDS(paste0(input_path, bioproject, "_WMS_ps_object.rds"))
  ps = subset_taxa(ps, Phylum != "Chordata")
  ps@sam_data[, 22] <- row.names(ps@sam_data)
  colnames(ps@sam_data)[22] <- "Run"
#   print(ps@sam_data$Run)
  for(i in (1:nrow(ps@sam_data))) {
    ps@sam_data[i, "SubGroup"] <- gsub(" ", "_", ps@sam_data[i, "SubGroup"])
    ps@sam_data[i, "SubGroup"] <- gsub("/", "_", ps@sam_data[i, "SubGroup"])
  }
  plot_krona(ps, paste0(output_path, bioproject, "_WMS_krona_runwise"), "Run", trim = T)

  print(paste0("Done for the BioProject: ", bioproject))
}
