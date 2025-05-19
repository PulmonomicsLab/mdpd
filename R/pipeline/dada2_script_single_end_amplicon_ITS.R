#This Rscript allows taxonomic assignments from raw FASTQ files using DADA2 pacakage for single end Amplicon-ITS bioprojects
#setting the paths
args=commandArgs(trailingOnly=TRUE)
input_path_prefix <- "/input_path/"
output_path_prefix <- "/output_path/"

#get the bioproject list
bioproject_list_file_name <- paste0(input_path_prefix, args[1])
bioprojects <- read.csv("list_of_bioprojects.csv", header=FALSE)[, 1]

print(paste0("Number of BioProjects = ", length(bioprojects)))
print("List of BioProjects = ")
print(bioprojects)

  library(dada2)
  library(phyloseq)
  library(unix)

options(lifecycle_verbosity = "quiet")
rlimit_as(1e12)

execCount <- 0
for (bioproject in bioprojects) {
  execCount <- execCount + 1
  print(paste0("Executing for the BioProject: ", bioproject, " (", execCount, "/", length(bioprojects), ")"))

  input_path <- paste0(input_path_prefix, bioproject, "/")
  output_path <- paste0(output_path_prefix, bioproject, "/")
#   print(input_path)
#   setwd(input_path)
  files<-list.files(input_path)
  metadata_file_name <- ""
  for (f in files)
      if(startsWith(f, bioproject) && endsWith(f, ".csv"))
          metadata_file_name <- f
   print(metadata_file_name)
#Read the fastq files
  fnFs_single <- sort(list.files(input_path, pattern="_R1.fq.gz", full.names = TRUE))
  sample.names <- sapply(strsplit(basename(fnFs_single), "_"), `[`, 1)
  filtFs_single <- file.path(paste0(output_path, "filtered"), paste0(sample.names, "_F_filtered.fastq.gz"))
  names(filtFs_single) <- sample.names
  out_single <- filterAndTrim(fnFs_single, filtFs_single, maxN=0, maxEE=c(2), truncLen=c(0), truncQ=2, rm.phix=TRUE, trimLeft = 15,
                      compress=TRUE, multithread=32)
  print(out_single)
  errF_single <- learnErrors(filtFs_single, multithread=32)
  dadaFs_single <- dada(filtFs_single, err=errF_single, multithread=32)
  dadaFs_single[[1]]
  seqtab_single <- makeSequenceTable(dadaFs_single)
  dim(seqtab_single)
  table(nchar(getSequences(seqtab_single)))

  seqtab.nochim_single <- removeBimeraDenovo(seqtab_single, method="consensus", multithread=32, verbose=TRUE)
  dim(seqtab.nochim_single)
  sum(seqtab.nochim_single)/sum(seqtab_single)
  getN_single <- function(x) sum(getUniques(x))
  track_single <- cbind(out_single, sapply(dadaFs_single, getN_single), rowSums(seqtab.nochim_single))
  colnames(track_single) <- c("input", "filtered", "denoisedF", "nonchim")
  rownames(track_single) <- sample.names
  print(track_single)
  write.csv(track_single, file = paste0(output_path, "track.csv"))

  taxa <- assignTaxonomy(seqtab.nochim_single, paste0(input_path_prefix, "sh_general_release_dynamic_04.04.2024.fasta"), multithread=32, minBoot = 100,  tryRC = TRUE)
  taxa.print <- taxa
  rownames(taxa.print) <- NULL
  print(taxa.print)
#read metadata file
  metadata = read.csv(paste0(input_path, metadata_file_name), row.names = 1, header = TRUE)
#phyloseq object
  ps <- phyloseq(otu_table(seqtab.nochim_single, taxa_are_rows=FALSE), tax_table(taxa), sample_data(metadata))
  ps
  dna <- Biostrings::DNAStringSet(taxa_names(ps))
  names(dna) <- taxa_names(ps)
  ps <- merge_phyloseq(ps, dna)
  taxa_names(ps) <- paste0("ASV", seq(ntaxa(ps)))
  asv_seqs <- colnames(seqtab.nochim_single)
  asv_headers <- vector(dim(seqtab.nochim_single)[2], mode="character")
  for (i in 1:dim(seqtab.nochim_single)[2]) {
    asv_headers[i] <- paste(">ASV", i, sep="_")
  }
  Asv_fasta <- c(rbind(asv_headers, asv_seqs))
  # write(Asv_fasta, "ASVs.fa")
  asv_tab <- t(seqtab.nochim_single)
  row.names(asv_tab) <- sub(">", "", asv_headers)
    write.table(asv_tab, paste0(output_path, "ASVs_counts.tsv"), sep="\t", quote=F, col.names=NA)
  write.csv(ps@otu_table, file = paste0(output_path, "otu_table.csv"))
  write.csv(ps@tax_table, file = paste0(output_path, "tax_table.csv"))
  saveRDS(ps, paste0(output_path, bioproject, "_Amplicon-ITS_ps_object.rds"))
  ps_in <- readRDS(paste0(output_path, bioproject, "_Amplicon-ITS_ps_object.rds"))
  print(ps_in)

   print(paste0("Done for the BioProject: ", bioproject))
}
