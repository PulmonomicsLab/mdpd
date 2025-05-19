#This Rscript allows taxonomic assignments from raw FASTQ files using DADA2 pacakage for paired end Amplicon-16S bioprojects
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

  files<-list.files(input_path)
  metadata_file_name <- ""
  for (f in files)
      if(startsWith(f, bioproject) && endsWith(f, ".csv"))
          metadata_file_name <- f
   print(metadata_file_name)
#Read the fastq files
  fnFs <- sort(list.files(input_path, pattern="_R1.fq.gz", full.names = TRUE))
  fnRs <- sort(list.files(input_path, pattern="_R2.fq.gz", full.names = TRUE))
  sample.names <- sapply(strsplit(basename(fnFs), "_"), `[`, 1)
  filtFs <- file.path(paste0(output_path, "filtered"), paste0(sample.names, "_F_filtered.fastq.gz"))
  filtRs <- file.path(paste0(output_path, "filtered"), paste0(sample.names, "_R_filtered.fastq.gz"))
  names(filtFs) <- sample.names
  names(filtRs) <- sample.names
  out <- filterAndTrim(fnFs, filtFs, fnRs, filtRs, maxN=0, maxEE=c(2,2), truncLen=c(0,0), truncQ=2, rm.phix=TRUE,
                      compress=TRUE, multithread=32)
  head(out)
  errF <- learnErrors(filtFs, multithread=32)
  errR <- learnErrors(filtRs, multithread=32)
  dadaFs <- dada(filtFs, err=errF, multithread=32)
  dadaRs <- dada(filtRs, err=errR, multithread=32)
  dadaFs[[1]]
  mergers <- mergePairs(dadaFs, filtFs, dadaRs, filtRs, verbose=TRUE)
  seqtab <- makeSequenceTable(mergers)
  seqtab.nochim <- removeBimeraDenovo(seqtab, method="consensus", multithread=32, verbose=TRUE)
  getN <- function(x) sum(getUniques(x))
  track <- cbind(out, sapply(dadaFs, getN), sapply(dadaRs, getN), sapply(mergers, getN), rowSums(seqtab.nochim))
  colnames(track) <- c("input", "filtered", "denoisedF", "denoisedR", "merged", "nonchim")
  rownames(track) <- sample.names
  print(track)
  write.csv(track, file = paste0(output_path, "track.csv"))
  taxa <- suppressMessages(assignTaxonomy(seqtab.nochim, paste0(input_path_prefix, "silva_nr99_v138.1_train_set.fa.gz"), multithread=32, minBoot = 100,  tryRC = TRUE))
  taxa.print <- taxa
  rownames(taxa.print) <- NULL
  print(taxa.print)
#read metadata fle
  metadata = read.csv(paste0(input_path, metadata_file_name), row.names = 1, header = TRUE)
#phyloseq object
  ps <- phyloseq(otu_table(seqtab.nochim, taxa_are_rows=FALSE), sample_data(metadata), tax_table(taxa))
  ps
  # otu_table(ps) <- t(otu_table(ps))
  dna <- Biostrings::DNAStringSet(taxa_names(ps))
  names(dna) <- taxa_names(ps)
  ps <- merge_phyloseq(ps, dna)
  print(ps)
  taxa_names(ps) <- paste0("ASV", seq(ntaxa(ps)))
  asv_seqs <- colnames(seqtab.nochim)
  asv_headers <- vector(dim(seqtab.nochim)[2], mode="character")
  for (i in 1:dim(seqtab.nochim)[2]) {
    asv_headers[i] <- paste(">ASV", i, sep="_")
  }
  Asv_fasta <- c(rbind(asv_headers, asv_seqs))
  write(Asv_fasta, paste0(output_path, "ASVs.fa"))
  asv_tab <- t(seqtab.nochim)
  row.names(asv_tab) <- sub(">", "", asv_headers)
  write.table(asv_tab, paste0(output_path, "ASVs_counts.tsv"), sep="\t", quote=F, col.names=NA)
  write.csv(ps@otu_table, file = paste0(output_path, "otu_table.csv"))
  write.csv(ps@tax_table, file = paste0(output_path, "tax_table.csv"))
  saveRDS(ps, paste0(output_path, bioproject, "_Amplicon-16S_ps_object.rds"))
  ps_in <- readRDS(paste0(output_path, bioproject, "_Amplicon-16S_ps_object.rds"))
  print(ps_in)

   print(paste0("Done for the BioProject: ", bioproject))
}


sh_general_release_dynamic_04.04.2024.fasta

