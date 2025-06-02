# R scripts

The R scripts used in the MDPD analyses are available here. These codes allow to perform the taxonomic assignment, saving .BIOM files, generate Krona plots, commounity structures and taxonomic annotations with their distribution.

## DADA2 pipline
These dada2 scripts allow to get the taxonomic assignments for both amplicon-16S, ITS and save the .BIOM file as RDS object for future use.
* `dada2_script_single_end_amplicon_16S.R`
* `dada2_script_paired_end_amplicon_16S.R`
* `dada2_script_single_end_amplicon_ITS.R`
* `dada2_script_paired_end_amplicon_16S.R`

## Taxonomic composition
The hierarchical distribution of the identified taxa is visualized with Krona plots.
* `krona_plot_script_amplicon.R`
* `krona_plot_script_WMS.R`

## Community structure
Generalized boosted linear models (GBLM) is used to find the microbial associations (both positive and negative) in each disease or healthy subtype.
* `bioproject_community_structure_amplicon.R`
* `bioproject_community_structure_wms.R`

## Taxonomic annotation
These scripts allow to retrieve the taxa annotations and their distributions among human body sites and in disease or healthy subtypes.
* `taxa_distribution.R`
* `taxa_info_finder.R`
