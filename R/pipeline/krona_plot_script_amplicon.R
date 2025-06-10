#This Rscript allows to get the Microbial composition (krona Plot) using psadd package and kronatools
#for Amplicon (16S and ITS)
args=commandArgs(trailingOnly=TRUE)
bioproject_file_path_prefix <- "/input_path/"
input_path_prefix <- "/input_path/"
output_path_prefix <- "output_path/"

#get the bioproject list
bioproject_list_file_name <- paste0(bioproject_file_path_prefix, args[1])
bioproject_file <- read.csv("list_of_bioprojects", header=FALSE)
bioprojects <- bioproject_file[, 1]
# columns <- bioproject_file[, 2]
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
  #read the phyloseq objects (amplicon-16S or amplicon-ITS) (one at a time)
  ps <- readRDS(paste0(input_path, bioproject, "_Amplicon-16S_ps_object.rds"/"_Amplicon-16S_ps_object.rds"))
  print(ps)
  isolationSources <- unique(ps@sam_data$IsolationSource)
  print(isolationSources)

  # Krona Plot Subgroup
  for (isolationSource in isolationSources) {
  #read phyloseq object _Amplicon-16S or _Amplicon-ITS (one at a time)
    ps <- readRDS(paste0(input_path, bioproject, "_Amplicon-ITS_ps_object", / "_Amplicon-ITS_ps_object.rds"))
    ps <- subset_samples(ps, IsolationSource == isolationSource)
    for(i in (1:nrow(ps@sam_data))) {
      ps@sam_data[i, "SubGroup"] <- gsub(" ", "_", ps@sam_data[i, "SubGroup"])
      ps@sam_data[i, "SubGroup"] <- gsub("/", "_", ps@sam_data[i, "SubGroup"])
      ps@sam_data[i, "SubGroup"] <- gsub(": ", "_", ps@sam_data[i, "SubGroup"])
      ps@sam_data[i, "SubGroup"] <- gsub(", ", "_", ps@sam_data[i, "SubGroup"])
    }
  #either _Amplicon-16S or _Amplicon-ITS (one at a time)
    plot_krona(ps, paste0(output_path, bioproject, "_Amplicon-16S", / "_Amplicon-ITS_", gsub(" ", "_", isolationSource), "_krona_subgroup"), "SubGroup", trim = T)
  }

  # Krona Plot Runwise
  #either _Amplicon-16S or _Amplicon-ITS (one at a time)
  ps <- readRDS(paste0(input_path, bioproject, "_Amplicon-16S_ps_object.rds", / "_Amplicon-ITS_ps_object.rds"))
  ps@sam_data[, 22] <- row.names(ps@sam_data)
  colnames(ps@sam_data)[22] <- "Run"
  print(ps@sam_data$Run)
  for(i in (1:nrow(ps@sam_data))) {
    ps@sam_data[i, "SubGroup"] <- gsub(" ", "_", ps@sam_data[i, "SubGroup"])
    ps@sam_data[i, "SubGroup"] <- gsub("/", "_", ps@sam_data[i, "SubGroup"])
  }
   #either _Amplicon-16S or _Amplicon-ITS (one at a time)
  plot_krona(ps, paste0(output_path, bioproject,"_Amplicon-16S_krona_runwise", / "_Amplicon-ITS_krona_runwise"), "Run", trim = T)

  print(paste0("Done for the BioProject: ", bioproject))
}
