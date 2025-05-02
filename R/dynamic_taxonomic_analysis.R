args <- commandArgs(trailingOnly=TRUE)
assayType <- args[1]
path <- args[2]

inputPath <- "input/biom/"
libraryPath <- "Rlibs/"

.libPaths(c(libraryPath, .libPaths()))
library(phyloseq, quietly=TRUE)
library(microeco, quietly=TRUE)
library(file2meco, quietly=TRUE)

tryCatch (
    {
        bioprojects <- read.table(paste0(path, "bioprojects.tsv"), header=FALSE)[, 1]
        runs <- read.table(paste0(path, "runs.tsv"), header=FALSE)[, 1]

        # print(assayType)
        # print(bioprojects)
        # print(runs)

        # Initialize params
        if (assayType == "WMS") {
            tax_rank <- "Species"
            tax_prefix <- "s__"
            rel_abund <- 0.0001
            freq <- 0.05
            pollution_filters = "Chordata"
        } else {
            tax_rank <- "Genus"
            tax_prefix <- "g__"
            rel_abund <- 0.0001
            freq <- 0.05
            pollution_filters = c("mitochondria", "chloroplast")
        }

        # Read biom
        subset_bioms = c()
        for (bioprojectID in bioprojects) {
            tryCatch(
                {
                    # Read biom RDS
                    ps <- readRDS(paste0(inputPath, bioprojectID, "_", assayType, "_ps_object.rds"))

                    # Create phyloseq object to meco object
                    ps@sam_data$Run <- rownames(ps@sam_data)
                    if(assayType != "WMS") {
                        ps <- tax_glom(ps, "Genus", NArm=TRUE, bad_empty=c(NA, "", " ", "\t", "g__"))
                        taxa_names(ps) <- ps@refseq
                    }
                    # print(ps)
                    suppressMessages(meco_object <- phyloseq2meco(ps))
                    suppressMessages(meco_object$tidy_dataset())

                    # Filter pollution
                    suppressMessages(meco_object$filter_pollution(taxa = pollution_filters))
                    suppressMessages(meco_object$tidy_dataset())

                    # Filter based on abundance and detection
                    suppressMessages(meco_object$filter_taxa(rel_abund = rel_abund, freq = freq))
                    suppressMessages(meco_object$tidy_dataset())

                    # meco_object$sample_table <- subset(meco_object$sample_table, (Assay.Type == assayType) & (Isolation.source == isolationSource))
                    suppressMessages(meco_object$sample_table <- subset(meco_object$sample_table, (Run %in% runs)))
                    suppressMessages(meco_object$tidy_dataset())

                    # Revert to phyloseq object from meco object
                    ps <- meco2phyloseq(meco_object)
                    # Add ps to subset_bioms
                    subset_bioms <- c(subset_bioms, ps)
                }, error = function(condition) {
                }, warning = function(condition) {}
            )
        }

        # Merge bioms to generate total_biom
        total_biom <- do.call(merge_phyloseq, as.list(subset_bioms))
        if (assayType != "WMS")
            total_biom <- tax_glom(total_biom, "Genus", NArm=TRUE, bad_empty=c(NA, "", " ", "\t", "g__"))
        # Add new column by merging SubGroup, IsolationSource, and BioProject
        total_biom@sam_data$SubGroup_IsolationSource_BioProject <- paste(total_biom@sam_data$SubGroup, total_biom@sam_data$IsolationSource, total_biom@sam_data$BioProject, sep="_")
        # Create total_meco_object from total_biom
        suppressMessages(total_meco_object <- phyloseq2meco(total_biom))
        suppressMessages(total_meco_object$tidy_dataset())

        # Perform trans_abund on total_meco_object
        suppressMessages(t1 <- trans_abund$new(dataset = total_meco_object, taxrank = tax_rank, ntaxa = 25, groupmean="SubGroup_IsolationSource_BioProject"))
        p <- t1$plot_heatmap()
        trim_taxa <- p$data[, c("Taxonomy", "Sample", "Abundance")]
        trim_taxa <- trim_taxa[with(trim_taxa, order(-ave(trim_taxa$Abundance, trim_taxa$Taxonomy, FUN=median))),]
        # print(trim_taxa)

        taxa_json <- "\"taxa\":["
        name_json <- "\"name\":["
        abundance_json <- "\"abundance\":["
        for (i in seq_len(nrow(trim_taxa))) {
            if (i == nrow(trim_taxa)) {
                taxa_json <- paste0(taxa_json, "\"", tax_prefix, trim_taxa[i, "Taxonomy"], "\"")
                name_json <- paste0(name_json, "\"", trim_taxa[i, "Sample"], "\"")
                abundance_json <- paste0(abundance_json, trim_taxa[i, "Abundance"])
            } else {
                taxa_json <- paste0(taxa_json, "\"", tax_prefix, trim_taxa[i, "Taxonomy"], "\",")
                name_json <- paste0(name_json, "\"", trim_taxa[i, "Sample"], "\",")
                abundance_json <- paste0(abundance_json, trim_taxa[i, "Abundance"], ",")
            }
        }
        taxa_json <- paste0(taxa_json, "]")
        name_json <- paste0(name_json, "]")
        abundance_json <- paste0(abundance_json, "]")

        out_json <- paste0("{", taxa_json, ",", name_json, ",", abundance_json, "}")
        cat(out_json)
    },
    error = function(condition) {
        out_json <- "{\"taxa\":[],\"name\":[],\"abundance\":[]}"
        cat(out_json)
    }
)

