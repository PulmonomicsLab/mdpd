## R codes for computational analysis

# Required packages
library(microbiomeMarker)
library(mia)
library(phyloseq)

# Read the biom file
Biom_file = "Disease_SampleSource_AssayType.biom" # biom file name
Data = import_biom(Biom_file, parseFunction = parse_taxonomy_default)

# Removing the artefacts from 16s amplicon data
Data_1 = subset_taxa(Data, !is.na(Kingdom) & !Kingdom %in% c("Holozoa", "Eukaryota", "Nucletmycea"))
Data_2 = subset_taxa(Data_1, Phylum != "Incertae sedis")
Data_3 = subset_taxa(Data_2, Class != "Incertae Sedis")
Data_4 = subset_taxa(Data_3, !is.na(Order) & !Order %in% c("Gammaproteobacteria Incertae Sedis", "Oxyphotobacteria Incertae Sedis", "Alphaproteobacteria Incertae Sedis", "Incertae Sedis", "uncultured"))
Data_5 = subset_taxa(Data_4, !is.na(Family) & !Family %in% c("Rhizobiales Incertae Sedis", "Coriobacteriales Incertae Sedis", "Puniceispirillaes Incertae Sedis", "Bacteroidales Incertae Sedis", "Entomoplasmatales Incertae Sedis", "Micrococcales Incertae Sedis", "Actinomycetales Incertae Sedis", "Desulfotomaculales Incertae Sedis", "Nitrococcales Incertae Sedis", "Synechococcales Incertae Sedis", "Azospirillales Incertae Sedis", "Brachyspirales Incertae Sedis", "Eurycoccales Incertae Sedis", "Flavobacteriales Incertae Sedis", "uncultured"))
Data_6 = subset_taxa(Data_5, !is.na(Genus) & !Genus %in% c("Incertae Sedis", "uncultured"))

# Removing the artefacts from WMS data
Data_1 = subset_taxa(Data, Phylum != "Chordata")
Data_2 = subset_taxa(Data_1, Order != "Bacteroidetes Order II. Incertae sedis")
Data_3 = subset_taxa(Data_2,  !is.na(Family) & !Family %in% c("Clostridiales   Family XIII. Incertae Sedis", "Clostridiales Family XVII. Incertae Sedis", "Clostridiales Family XVI. Incertae Sedis", "Thermoanaerobacterales Family III. Incertae Sedis", "Thermoanaerobacterales Family IV. Incertae Sedis"))


# Agglomeration into genus rank for 16s amplicon data
Data_agglomerate_genus = phyloseq::tax_glom(Data_6, "Genus", NArm = TRUE)
# agglomerate into species rank for WMS data
Data_agglomerate_species = phyloseq::tax_glom(Data_3, "Species", NArm = TRUE)

# Normalization and analysis of the data

# Perform normalization and differential analysis using LEfSe at genus rank for 16s amplicon data
Lefse_genus = run_lefse(Data_agglomerate_genus, norm = "CPM", wilcoxon_cutoff = 0.05, group= "SubGroup", kw_cutoff= 0.01, multigrp_strat = TRUE, lda_cutoff = 2, taxa_rank = "Genus")
# Perform normalization and differential analysis using LEfSe at species rank for WMS data
Lefse_species = run_lefse(Data_agglomerate_species, norm = "CPM", wilcoxon_cutoff = 0.05, group= "SubGroup", kw_cutoff= 0.01, multigrp_strat = TRUE, lda_cutoff = 2, taxa_rank = "Species")

# Normalization of the data (transform abundances to percentage) for heatmap and merge sample BioProject-wise
merged.data = merge_samples(Data_6, group = "BioProject")
merged.percent = transform_sample_counts(merged.data, function(x) x*100/sum(x))

# Normalization of the data between 0-1 for heatmap
Data_normalize = function(x) {(x - min(x)) / (max(x) - min(x))}
Final_output = fun_range(x = Data_normalize)

# Normalization of the data (relative abundance) to get prevalent taxa
Data_summarized = makeTreeSummarizedExperimentFromPhyloseq(Data_agglomerate_genus/species)
Data_transform = transformSamples(Data_summarized, method = "relabundance")
prevalent_taxa = getPrevalentTaxa(Data_transform, detection = 0.0001, prevalence = 50/100, rank = "Genus/Species", sort = TRUE)
Prevalence_value = getPrevalence(Data_transform, detection = 0.0001, prevalence = 50/100)
