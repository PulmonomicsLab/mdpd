#This Rscript allows to obtain the information of the microbes
library(bugphyzz)
library(taxonomizr)

# prepareDatabase('accessionTaxa.sql')


bp <- importBugphyzz()

taxa_list <- unique(read.csv("taxa_distribution.csv", header = TRUE, sep = ",")[, 2])
taxa_list <- gsub("_", " ", taxa_list)
length(taxa_list)

taxa <- c()
taxIds <- c()
domains <- c()
bf_values <- c()
bf_evidences <- c()
gs_values <- c()
gs_evidences <- c()
sf_values <- c()
sf_evidences <- c()
ap_values <- c()
ap_evidences <- c()
ges_values <- c()
ges_evidences <- c()
cg_values <- c()
cg_evidences <- c()
amr_values <- c()
amr_evidences <- c()
ams_values <- c()
ams_evidences <- c()
shape_values <- c()
shape_evidences <- c()
i <- 1
for (t in taxa_list) {
  print(paste0(i, "/", length(taxa_list), " - Searching taxa: ", t))
  taxa <- c(taxa, t)
  taxId <- getId(c(t),'accessionTaxa.sql')[1]
  taxIds <- c(taxIds, taxId)
  domains <- c(domains, getTaxonomy(c(taxId),'accessionTaxa.sql')[1, "superkingdom"])
  
  ## biofilm formation
  if(t %in% bp$`biofilm formation`$Taxon_name) {
    bf_values <- c(bf_values, bp$`biofilm formation`[bp$`biofilm formation`$Taxon == t, "Attribute_value"])
    bf_evidences <- c(bf_evidences, bp$`biofilm formation`[bp$`biofilm formation`$Taxon == t, "Evidence"])
  } else {
    bf_values <- c(bf_values, "No information available")
    bf_evidences <- c(bf_evidences, "No information available")
  }
  
  ## gram stain
  if(t %in% bp$`gram stain`$Taxon_name) {
    gs_values <- c(gs_values, bp$`gram stain`[bp$`gram stain`$Taxon == t, "Attribute_value"][1])
    gs_evidences <- c(gs_evidences, bp$`gram stain`[bp$`gram stain`$Taxon == t, "Evidence"][1])
  } else {
    gs_values <- c(gs_values, "No information available")
    gs_evidences <- c(gs_evidences, "No information available")
  }
  
  ## spore formation
  if(t %in% bp$`spore formation`$Taxon_name) {
    sf_values <- c(sf_values, bp$`spore formation`[bp$`spore formation`$Taxon == t, "Attribute_value"][1])
    sf_evidences <- c(sf_evidences, bp$`spore formation`[bp$`spore formation`$Taxon == t, "Evidence"][1])
  } else {
    sf_values <- c(sf_values, "No information available")
    sf_evidences <- c(sf_evidences, "No information available")
  }
  
  ## aerophilicity
  if(t %in% bp$aerophilicity$Taxon_name) {
    ap_value <- toString(bp$aerophilicity[bp$aerophilicity$Taxon == t, "Attribute_value"], sep=";")
    ap_evidence <- toString(bp$aerophilicity[bp$aerophilicity$Taxon == t, "Evidence"], sep=";")
    ap_values <- c(ap_values, ap_value)
    ap_evidences <- c(ap_evidences, ap_evidence)
  } else {
    ap_values <- c(ap_values, "No information available")
    ap_evidences <- c(ap_evidences, "No information available")
  }
  
  ## genome size
  if(t %in% bp$`genome size`$Taxon_name) {
    ges_values <- c(ges_values, bp$`genome size`[bp$`genome size`$Taxon == t, "Attribute_value"])
    ges_evidences <- c(ges_evidences, bp$`genome size`[bp$`genome size`$Taxon == t, "Evidence"])
  } else {
    ges_values <- c(ges_values, "No information available")
    ges_evidences <- c(ges_evidences, "No information available")
  }
  
  ## coding genes
  if(t %in% bp$`coding genes`$Taxon_name) {
    cg_values <- c(cg_values, bp$`coding genes`[bp$`coding genes`$Taxon == t, "Attribute_value"])
    cg_evidences <- c(cg_evidences, bp$`coding genes`[bp$`coding genes`$Taxon == t, "Evidence"])
  } else {
    cg_values <- c(cg_values, "No information available")
    cg_evidences <- c(cg_evidences, "No information available")
  }
  
  ## antimicrobial resistance
  if(t %in% bp$`antimicrobial resistance`$Taxon_name) {
    amr_value <- toString(bp$`antimicrobial resistance`[bp$`antimicrobial resistance`$Taxon == t, "Attribute_value"], sep=";")
    amr_evidence <- toString(bp$`antimicrobial resistance`[bp$`antimicrobial resistance`$Taxon == t, "Evidence"], sep=";")
    amr_values <- c(amr_values, amr_value)
    amr_evidences <- c(amr_evidences, amr_evidence)
  } else {
    amr_values <- c(amr_values, "No information available")
    amr_evidences <- c(amr_evidences, "No information available")
  }
  
  ## antimicrobial sensitivity
  if(t %in% bp$`antimicrobial sensitivity`$Taxon_name) {
    ams_value <- toString(bp$`antimicrobial sensitivity`[bp$`antimicrobial sensitivity`$Taxon == t, "Attribute_value"], sep=";")
    ams_evidence <- toString(bp$`antimicrobial sensitivity`[bp$`antimicrobial sensitivity`$Taxon == t, "Evidence"], sep=";")
    ams_values <- c(ams_values, ams_value)
    ams_evidences <- c(ams_evidences, ams_evidence)
  } else {
    ams_values <- c(ams_values, "No information available")
    ams_evidences <- c(ams_evidences, "No information available")
  }
  
  ## shape
  if(t %in% bp$shape$Taxon_name) {
    shape_value <- toString(bp$shape[bp$shape$Taxon == t, "Attribute_value"], sep=";")
    shape_evidence <- toString(bp$shape[bp$shape$Taxon == t, "Evidence"], sep=";")
    shape_values <- c(shape_values, shape_value)
    shape_evidences <- c(shape_evidences, shape_evidence)
  } else {
    shape_values <- c(shape_values, "No information available")
    shape_evidences <- c(shape_evidences, "No information available")
  }
  
  i <- i + 1
}
taxa_info_table <- data.frame(
  Taxa = taxa,
  NCBITaxaID = taxIds,
  Domain = domains,
  BiofilmFormation = bf_values,
  BiofilmFormationEvidence = bf_evidences,
  GramStain = gs_values,
  GramStainEvidence = gs_evidences,
  SporeFormation = sf_values,
  SporeFormationEvidence = sf_evidences,
  Aerophilicity = ap_values,
  AerophilicityEvidence = ap_evidences,
  GenomeSize = ges_values,
  GenomeSizeEvidence = ges_evidences,
  CodingGenes = cg_values,
  CodingGenesEvidence = cg_evidences,
  AntimicrobialResistance = amr_values,
  AntimicrobialResistanceEvidence = amr_evidences,
  AntimicrobialSensitivity = ams_values,
  AntimicrobialSensitivityEvidence = ams_evidences,
  Shape = shape_values,
  ShapeEvidence = shape_evidences
)
write.csv(taxa_info_table, file = "taxa_info_table.tsv", sep="\t")
