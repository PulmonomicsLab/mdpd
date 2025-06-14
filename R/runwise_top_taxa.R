args <- commandArgs(trailingOnly=TRUE)
runID <- args[1]
bioprojectID <- args[2]
assayType <- args[3]

inputPath <- "input/biom/"
libraryPath <- "Rlibs/"

.libPaths(c(libraryPath, .libPaths()))
library(phyloseq, quietly=TRUE)
library(microeco, quietly=TRUE)
library(file2meco, quietly=TRUE)

tryCatch (
    {
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
        ps <- readRDS(paste0(inputPath, bioprojectID, "_", assayType, "_ps_object.rds"))

        # Create phyloseq object to meco object
        suppressMessages(meco_object <- phyloseq2meco(ps))
        suppressMessages(meco_object$tidy_dataset())
        # print(ps)

        # Filter pollution
        suppressMessages(meco_object$filter_pollution(taxa = pollution_filters))
        suppressMessages(meco_object$tidy_dataset())

        # Filter based on abundance and detection
        suppressMessages(meco_object$filter_taxa(rel_abund = rel_abund, freq = freq))
        suppressMessages(meco_object$tidy_dataset())

        meco_object$sample_table$Run <- rownames(meco_object$sample_table)
        suppressMessages(meco_object$sample_table <- subset(meco_object$sample_table, Run == runID))
        suppressMessages(meco_object$tidy_dataset())

        # Bar plot Genus
        suppressMessages(t3 <- trans_abund$new(dataset = meco_object, taxrank = tax_rank, ntaxa = 50))
        trim_taxa <- t3$data_abund[t3$data_abund$Taxonomy != "unidentified", c("Taxonomy", "Abundance")]
        ordered_taxa <- trim_taxa[order(trim_taxa$Abundance, decreasing=TRUE),]
        # print(ordered_taxa)

        taxa_json <- "\"taxa\":["
        abundance_json <- "\"abundances\":["
        if (nrow(ordered_taxa) < 10) {
            end <- nrow(ordered_taxa)
        } else {
            end <- 10
        }
        for (i in seq_len(end)) {
            if (i == end) {
                taxa_json <- paste0(taxa_json, "\"", tax_prefix, ordered_taxa[i, "Taxonomy"], "\"")
                abundance_json <- paste0(abundance_json, ordered_taxa[i, "Abundance"])
            } else {
                taxa_json <- paste0(taxa_json, "\"", tax_prefix, ordered_taxa[i, "Taxonomy"], "\",")
                abundance_json <- paste0(abundance_json, ordered_taxa[i, "Abundance"], ",")
            }
        }
        taxa_json <- paste0(taxa_json, "]")
        abundance_json <- paste0(abundance_json, "]")

        out_json <- paste0("{", taxa_json, ",", abundance_json, "}")
        cat(out_json)
    },
    error = function(condition) {
        out_json <- "{\"taxa\":[],\"abundances\":[]}"
        cat(out_json)
    }
)
