args <- commandArgs(trailingOnly=TRUE)
assayType <- args[1]
path <- args[2]
method <- args[3]
alpha <- as.double(args[4])
p_adjust_method <- args[5]
filter_thres <- as.double(args[6])
taxa_level <- args[7]
threshold <- as.double(args[8])

inputPath <- "input/biom/"
libraryPath <- "Rlibs/"

.libPaths(c(libraryPath, .libPaths()))
library(phyloseq, quietly=TRUE)
library(microeco, quietly=TRUE)
library(file2meco, quietly=TRUE)
suppressMessages(library(ANCOMBC, quietly = TRUE))
library(MMUPHin, quietly=TRUE)
library(stringr, quietly=TRUE)
suppressMessages(library(dplyr, quietly=TRUE))
suppressMessages(library(tidyr, quietly=TRUE))
suppressMessages(library(tidyverse, quietly=TRUE))

set.seed(0)

# Function to run LINDA with a chosen reference group
run_linda_ref <- function(ref, grp) {
    suppressMessages(total_meco_object$sample_table$SubGroup_IsolationSource_BioProject <- relevel(total_meco_object$sample_table$SubGroup_IsolationSource_BioProject, ref))
    suppressWarnings(
        suppressMessages(
            t1 <- trans_diff$new(
                dataset = total_meco_object,
                method = method,
                alpha = alpha,
                group = "SubGroup_IsolationSource_BioProject",
                filter_thres = filter_thres,
                taxa_level = taxa_level,
                p_adjust_method = p_adjust_method,
                verbose = FALSE
            )
        )
    )
    if (!is.null(t1$res_diff)) {
        t1$res_diff$ref_group <- ref
    }
    return(t1$res_diff)
}

run_linda_ref_adj <- function(ref, grp) {
    suppressMessages(total_meco_object_adj$sample_table$SubGroup_IsolationSource <- relevel(total_meco_object_adj$sample_table$SubGroup_IsolationSource, ref))
    suppressWarnings(
        suppressMessages(
            t1 <- trans_diff$new(
                dataset = total_meco_object_adj,
                method = method,
                alpha = alpha,
                group = "SubGroup_IsolationSource",
                filter_thres = filter_thres,
                taxa_level = taxa_level,
                p_adjust_method = p_adjust_method,
                verbose = FALSE
            )
        )
    )
    if (!is.null(t1$res_diff)) {
        t1$res_diff$ref_group <- ref
    }
    return(t1$res_diff)
}

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
        # Add new column by merging SubGroup and IsolationSource
        total_biom@sam_data$SubGroup_IsolationSource <- paste(total_biom@sam_data$SubGroup, total_biom@sam_data$IsolationSource, sep="_")
        # Create total_meco_object from total_biom
        suppressMessages(total_meco_object <- phyloseq2meco(total_biom))
        suppressMessages(total_meco_object$tidy_dataset())

#         suppressWarnings(
#             suppressMessages(
#                 t1 <- trans_diff$new(
#                     dataset = total_meco_object,
#                     method = method,
#                     alpha = alpha,
#                     lefse_subgroup = NULL,
#                     group = "SubGroup_IsolationSource_BioProject",
#                     filter_thres = filter_thres,
#                     taxa_level = taxa_level,
#                     p_adjust_method = p_adjust_method
#                 )
#             )
#         )
        if (method == "lefse") {
            suppressWarnings(
                suppressMessages(
                    t1 <- trans_diff$new(
                        dataset = total_meco_object,
                        method = method,
                        alpha = alpha,
                        lefse_subgroup = NULL,
                        group = "SubGroup_IsolationSource_BioProject",
                        filter_thres = filter_thres,
                        taxa_level = taxa_level,
                        p_adjust_method = p_adjust_method
                    )
                )
            )
            suppressWarnings(suppressMessages(p <- t1$plot_diff_bar(threshold = threshold, use_number = 1:200)))
            trim_lda <- p$data[, c("Taxa", "Group", "Value", "P.adj", "Significance")]
        } else if (method == "ALDEx2_t") {
            suppressWarnings(
                suppressMessages(
                    t1 <- trans_diff$new(
                        dataset = total_meco_object,
                        method = method,
                        alpha = alpha,
#                         lefse_subgroup = NULL,
                        group = "SubGroup_IsolationSource_BioProject",
                        filter_thres = filter_thres,
                        taxa_level = taxa_level,
                        p_adjust_method = p_adjust_method
                    )
                )
            )
            suppressWarnings(suppressMessages(p <- t1$res_diff))
            trim_lda <- p[, c("Taxa", "Comparison", "diff.btw", "P.adj", "Significance")]
            trim_lda <- trim_lda |> filter(P.adj <= alpha)
            colnames(trim_lda) <- c("Taxa", "Group", "Value", "P.adj", "Significance")
            if (nrow(trim_lda) > 0) {
                for (i in 1:nrow(trim_lda)) {
                    taxa_splits <- strsplit(as.character(trim_lda[i, "Taxa"]), "\\|")[[1]]
                    trim_lda[i, "Taxa"] <- taxa_splits[length(taxa_splits)]
                }
            }
            trim_lda <- trim_lda[abs(trim_lda$Value) > threshold & (trim_lda$Taxa) != "s__" & (trim_lda$Taxa) != "g__" & (trim_lda$Taxa) != "f__" & (trim_lda$Taxa) != "o__", ]
        } else if (method == "linda") {
            suppressMessages(total_meco_object$sample_table$SubGroup_IsolationSource_BioProject <- factor(total_meco_object$sample_table$SubGroup_IsolationSource_BioProject))
            # Detect all group levels automatically
            suppressMessages(group_levels <- levels(total_meco_object$sample_table$SubGroup_IsolationSource_BioProject))
            # Run LINDA for all possible reference groups
            suppressMessages(res_list <- lapply(group_levels, run_linda_ref))
            # Combine all results into one data frame
            suppressMessages(all_results <- bind_rows(res_list))
            # Normalize comparison names to remove reversed duplicates
            suppressMessages(all_results_clean <- all_results %>%
                tidyr::separate(Comparison, into = c("Group2", "Group1"), sep = " - ") %>%
                rowwise() %>%
                mutate(
                    # Create a normalized identifier (alphabetical order)
                    PairID = paste(sort(c(Group1, Group2), decreasing = TRUE),
               collapse = " vs ")
                ) %>%
                ungroup() %>%
                distinct(PairID, Taxa, .keep_all = TRUE) %>%
                # Keep the readable comparison column
                mutate(Comparison = PairID) %>%
                select(-PairID))
                # Optional: reorder columns for clarity
            suppressMessages(all_results_clean <- all_results_clean %>%
                select(Comparison, Taxa, everything()))
            suppressWarnings(suppressMessages(p <- all_results_clean))
            trim_lda <- p[, c("Taxa", "Comparison", "log2FoldChange", "P.adj", "Significance")]
            trim_lda <- trim_lda |> filter(P.adj <= alpha)
            colnames(trim_lda) <- c("Taxa", "Group", "Value", "P.adj", "Significance")
            if (nrow(trim_lda) > 0) {
                for (i in 1:nrow(trim_lda)) {
                    taxa_splits <- strsplit(as.character(trim_lda[i, "Taxa"]), "\\|")[[1]]
                    trim_lda[i, "Taxa"] <- taxa_splits[length(taxa_splits)]
                }
            }
            trim_lda <- trim_lda[abs(trim_lda$Value) > threshold & (trim_lda$Taxa) != "s__" & (trim_lda$Taxa) != "g__" & (trim_lda$Taxa) != "f__" & (trim_lda$Taxa) != "o__", ]
        } else if (method == "ancombc2") {
            suppressWarnings(suppressMessages(total_po <- meco2phyloseq(total_meco_object)))
            suppressWarnings(
                suppressMessages(
#                     t1 <- trans_diff$new(
#                         dataset = total_meco_object,
#                         method = method,
#                         alpha = alpha,
# #                         lefse_subgroup = NULL,
#                         group = "SubGroup_IsolationSource_BioProject",
#                         filter_thres = filter_thres,
#                         taxa_level = taxa_level,
#                         p_adjust_method = p_adjust_method,
#                         pairwise = TRUE
#                     )
                    t1 <- ancombc2(
                        total_po,
                        tax_level = taxa_level,
                        fix_formula = "SubGroup_IsolationSource_BioProject",
                        group = "SubGroup_IsolationSource_BioProject",
                        alpha = alpha,
                        p_adj_method = p_adjust_method,
                        pairwise = TRUE,
                        n_cl = 16,
                        prv_cut = filter_thres,
                        verbose = FALSE
                    )
                )
            )
            if (length(unique(total_po@sam_data$SubGroup_IsolationSource_BioProject)) > 2) {
                w <- t1$res_pair
                ref_group <- sort(unique(total_po@sam_data$SubGroup_IsolationSource_BioProject))[1]
                prefixes <- c("lfc", "se", "W", "p", "q", "diff", "passed_ss")
                for (pref in prefixes) {
                    single_cols <- grep(paste0("^", pref, "_SubGroup_IsolationSource_BioProject(?!.*SubGroup_IsolationSource_BioProject)"), names(w), perl = TRUE, value = TRUE)
                    for (col in single_cols) {
                        new_name <- paste0(col, "_SubGroup_IsolationSource_BioProject", ref_group)
                        names(w)[names(w) == col] <- new_name
                    }
                }
                res_pair <- w |>
                    dplyr::select(taxon, contains("lfc"), contains ("q_"), contains("diff")) |>
                    filter(if_any(contains("diff"), ~ .x == TRUE))
                res_long <- res_pair |>
                    pivot_longer(cols = -taxon,names_to = c(".value", "comparison"),
                                names_pattern = "(lfc|q|diff)_(.*)") |>
                    mutate(
                        comparison = str_remove_all(comparison, "SubGroup_IsolationSource_BioProject")
                    ) |>
                    filter(q <= alpha)
                suppressWarnings(suppressMessages(p <- res_long))
                trim_lda <- p[, c("taxon", "comparison", "lfc", "q")]
            } else {
#                 w <- t1$res
#                 res_long <- w |>
#                     filter(P.adj <= alpha)
#                 res_long <- as.data.frame(subset(res_long, Factors != "(Intercept)"))
#                 comparison_label <- sort(unique(total_meco_object$sample_table$SubGroup_IsolationSource_BioProject), decreasing = TRUE)
#                 if (nrow(res_long) > 0)
#                     res_long[, "Factors"] <- rep(paste0(comparison_label[1], "_", comparison_label[2]), nrow(res_long))
#                 suppressWarnings(suppressMessages(p <- res_long))
                w <- t1$res
                ref_group <- sort(unique(total_po@sam_data$SubGroup_IsolationSource_BioProject))[1]
                prefixes <- c("lfc", "se", "W", "p", "q", "diff", "passed_ss")
                for (pref in prefixes) {
                  single_cols <- grep(paste0("^", pref, "_SubGroup_IsolationSource_BioProject(?!.*SubGroup_IsolationSource_BioProject)"), names(w), perl = TRUE, value = TRUE)
                  for (col in single_cols) {
                    new_name <- paste0(col, "_SubGroup_IsolationSource_BioProject", ref_group)
                    names(w)[names(w) == col] <- new_name
                  }
                }
                res_pair <- w |>
                  dplyr::select(taxon, contains("lfc"), contains ("q_"), contains("diff")) |>
                  filter(if_any(contains("diff"), ~ .x == TRUE))
                res_long <- res_pair |>
                  pivot_longer(cols = -taxon,names_to = c(".value", "comparison"),
                               names_pattern = "(lfc|q|diff)_(.*)") |>
                  mutate(
                    comparison = str_remove_all(comparison, "SubGroup_IsolationSource_BioProject")
                  ) |>
                  filter(q <= alpha)
                res_long <- as.data.frame(subset(res_long, comparison != "(Intercept)"))
#                 clean_taxon <- function(taxon) {
#                 # Remove numeric suffixes, but keep taxons with names (e.g., g__Moraxella)
#                 gsub("__\\d+", "", taxon)
#                 }
#                 # Apply the cleaning function
#                 res_long$taxon <- sapply(res_long$taxon, clean_taxon)
                suppressWarnings(suppressMessages(p <- res_long))
                trim_lda <- p[, c("taxon", "comparison", "lfc", "qs")]
            }

            colnames(trim_lda) <- c("Taxa", "Group", "Value", "P.adj")
            if (nrow(trim_lda) > 0) {
                trim_lda[, "Significance"] <- ""
                for (i in 1:nrow(trim_lda)) {
                    taxa_splits <- strsplit(as.character(trim_lda[i, "Taxa"]), "\\|")[[1]]
                    trim_lda[i, "Taxa"] <- taxa_splits[length(taxa_splits)]
                }
            }
            trim_lda <- trim_lda[abs(trim_lda$Value) > threshold & (trim_lda$Taxa) != "s__" & (trim_lda$Taxa) != "g__" & (trim_lda$Taxa) != "f__" & (trim_lda$Taxa) != "o__", ]
        }

#         else {
#             suppressWarnings(suppressMessages(p <- t1$plot_diff_bar(use_number = 1:200)))
#             trim_lda <- p$data[, c("Taxa", "Group", "logFC", "P.unadj", "P.adj", "Significance")]
#             colnames(trim_lda) <- c("Taxa", "Group", "Value", "P.unadj", "P.adj", "Significance")
#             trim_lda <- trim_lda[abs(trim_lda$Value) > threshold & (trim_lda$Taxa) != "s__" & (trim_lda$Taxa) != "g__" & (trim_lda$Taxa) != "f__" & (trim_lda$Taxa) != "o__", ]
#         }

        p_adjust_json <- paste0("\"p_adjust\":\"", p_adjust_method, "\"")
        taxa_json <- "\"taxa\":["
        subgroup_json <- "\"subgroup\":["
        abundance_json <- "\"value\":["
        pval_json <- "\"pval\":["
        padj_json <- "\"padj\":["
        significance_json <- "\"significance\":["
        i <- 1
        while (i <= nrow(trim_lda)) {
            if (i == nrow(trim_lda)) {
                taxa_json <- paste0(taxa_json, "\"", trim_lda[i, "Taxa"], "\"")
                subgroup_json <- paste0(subgroup_json, "\"", trim_lda[i, "Group"], "\"")
                abundance_json <- paste0(abundance_json, trim_lda[i, "Value"])
                pval_json <- paste0(pval_json, "\"\"")
                padj_json <- paste0(padj_json, trim_lda[i, "P.adj"])
                significance_json <- paste0(significance_json, "\"", trim_lda[i, "Significance"], "\"")
            } else {
                taxa_json <- paste0(taxa_json, "\"", trim_lda[i, "Taxa"], "\",")
                subgroup_json <- paste0(subgroup_json, "\"", trim_lda[i, "Group"], "\",")
                abundance_json <- paste0(abundance_json, trim_lda[i, "Value"], ",")
                pval_json <- paste0(pval_json, "\"\"", ",")
                padj_json <- paste0(padj_json, trim_lda[i, "P.adj"], ",")
                significance_json <- paste0(significance_json, "\"", trim_lda[i, "Significance"], "\",")
            }
            i <- i + 1
        }
        taxa_json <- paste0(taxa_json, "]")
        subgroup_json <- paste0(subgroup_json, "]")
        abundance_json <- paste0(abundance_json, "]")
        pval_json <- paste0(pval_json, "]")
        padj_json <- paste0(padj_json, "]")
        significance_json <- paste0(significance_json, "]")
        lda_out_json <- paste0("{", p_adjust_json, ",", taxa_json, ",", subgroup_json, ",", abundance_json, ",", pval_json, ",", padj_json, ",", significance_json, "}")




        suppressWarnings(suppressMessages(
            fit_adjust_batch <- adjust_batch(
                feature_abd = total_biom@otu_table,batch = "BioProject",
    #             covariates = "SubGroup",
                data = as.matrix(total_biom@sam_data),
                control = list(verbose = FALSE, diagnostic_plot = NULL, maxit = 100)
            )
        ))

        # MMUPHin adjusted abundance table
        feature_abd_adj <- fit_adjust_batch$feature_abd_adj

        # Ensure it's a numeric matrix
        feature_abd_adj <- as.matrix(feature_abd_adj)

        # Build new phyloseq components
        otu_adj <- otu_table(as.data.frame(feature_abd_adj), taxa_are_rows = TRUE)  # transpose so taxa = rows
        tax <- tax_table(total_biom)
        samp <- sample_data(total_biom)

        # Construct new phyloseq object
        total_biom_adj <- phyloseq(otu_adj, tax, samp)

        suppressMessages(total_meco_object_adj <- phyloseq2meco(total_biom_adj))
        suppressMessages(total_meco_object_adj$tidy_dataset())

#         suppressWarnings(
#             suppressMessages(
#                 t1 <- trans_diff$new(
#                     dataset = total_meco_object_adj,
#                     method = method,
#                     alpha = alpha,
#                     lefse_subgroup = NULL,
#                     group = "SubGroup_IsolationSource",
#                     filter_thres = filter_thres,
#                     taxa_level = taxa_level,
#                     p_adjust_method = p_adjust_method
#                 )
#             )
#         )
        if (method == "lefse") {
            suppressWarnings(
                suppressMessages(
                    t1 <- trans_diff$new(
                        dataset = total_meco_object_adj,
                        method = method,
                        alpha = alpha,
                        lefse_subgroup = NULL,
                        group = "SubGroup_IsolationSource",
                        filter_thres = filter_thres,
                        taxa_level = taxa_level,
                        p_adjust_method = p_adjust_method
                    )
                )
            )
            suppressWarnings(suppressMessages(p <- t1$plot_diff_bar(threshold = threshold, use_number = 1:200)))
            trim_lda <- p$data[, c("Taxa", "Group", "Value", "P.adj", "Significance")]
        } else if (method == "ALDEx2_t") {
            suppressWarnings(
                suppressMessages(
                    t1 <- trans_diff$new(
                        dataset = total_meco_object_adj,
                        method = method,
                        alpha = alpha,
                        lefse_subgroup = NULL,
                        group = "SubGroup_IsolationSource",
                        filter_thres = filter_thres,
                        taxa_level = taxa_level,
                        p_adjust_method = p_adjust_method
                    )
                )
            )
            suppressWarnings(suppressMessages(p <- t1$res_diff))
            trim_lda <- p[, c("Taxa", "Comparison", "diff.btw", "P.adj", "Significance")]
            trim_lda <- trim_lda |> filter(P.adj <= alpha)
            colnames(trim_lda) <- c("Taxa", "Group", "Value", "P.adj", "Significance")
            if (nrow(trim_lda) > 0) {
                for (i in 1:nrow(trim_lda)) {
                    taxa_splits <- strsplit(as.character(trim_lda[i, "Taxa"]), "\\|")[[1]]
                    trim_lda[i, "Taxa"] <- taxa_splits[length(taxa_splits)]
                }
            }
            trim_lda <- trim_lda[abs(trim_lda$Value) > threshold & (trim_lda$Taxa) != "s__" & (trim_lda$Taxa) != "g__" & (trim_lda$Taxa) != "f__" & (trim_lda$Taxa) != "o__", ]
        } else if (method == "linda") {
            suppressMessages(total_meco_object_adj$sample_table$SubGroup_IsolationSource <- factor(total_meco_object_adj$sample_table$SubGroup_IsolationSource))
            # Detect all group levels automatically
            suppressMessages(group_levels <- levels(total_meco_object_adj$sample_table$SubGroup_IsolationSource))
            # Run LINDA for all possible reference groups
            suppressMessages(res_list <- lapply(group_levels, run_linda_ref_adj))
            # Combine all results into one data frame
            suppressMessages(all_results <- bind_rows(res_list))
            # Normalize comparison names to remove reversed duplicates
            suppressMessages(all_results_clean <- all_results %>%
                tidyr::separate(Comparison, into = c("Group2", "Group1"), sep = " - ") %>%
                rowwise() %>%
                mutate(
                    # Create a normalized identifier (alphabetical order)
                    PairID =paste(sort(c(Group1, Group2), decreasing = TRUE),
               collapse = " vs ")
                ) %>%
                ungroup() %>%
                distinct(PairID, Taxa, .keep_all = TRUE) %>%
                # Keep the readable comparison column
                mutate(Comparison = PairID) %>%
                select(-PairID))
                # Optional: reorder columns for clarity
            suppressMessages(all_results_clean <- all_results_clean %>%
                select(Comparison, Taxa, everything()))
            suppressWarnings(suppressMessages(p <- all_results_clean))
            trim_lda <- p[, c("Taxa", "Comparison", "log2FoldChange", "P.adj", "Significance")]
            trim_lda <- trim_lda |> filter(P.adj <= alpha)
            colnames(trim_lda) <- c("Taxa", "Group", "Value", "P.adj", "Significance")
            if (nrow(trim_lda) > 0) {
                for (i in 1:nrow(trim_lda)) {
                    taxa_splits <- strsplit(as.character(trim_lda[i, "Taxa"]), "\\|")[[1]]
                    trim_lda[i, "Taxa"] <- taxa_splits[length(taxa_splits)]
                }
            }
            trim_lda <- trim_lda[abs(trim_lda$Value) > threshold & (trim_lda$Taxa) != "s__" & (trim_lda$Taxa) != "g__" & (trim_lda$Taxa) != "f__" & (trim_lda$Taxa) != "o__", ]
        } else if (method == "ancombc2") {
            suppressWarnings(suppressMessages(total_po_adj <- meco2phyloseq(total_meco_object_adj)))
            suppressWarnings(
                suppressMessages(
#                     t1 <- trans_diff$new(
#                         dataset = total_meco_object_adj,
#                         method = method,
#                         alpha = alpha,
#                         group = "SubGroup_IsolationSource",
#                         filter_thres = filter_thres,
#                         taxa_level = taxa_level,
#                         p_adjust_method = p_adjust_method,
#                         pairwise = TRUE
#                     )
                    t1 <- ancombc2(
                        total_po_adj,
                        tax_level = taxa_level,
                        fix_formula = "SubGroup_IsolationSource",
                        group = "SubGroup_IsolationSource",
                        alpha = alpha,
                        p_adj_method = p_adjust_method,
                        pairwise = TRUE,
                        n_cl = 16,
                        prv_cut = filter_thres,
                        verbose = FALSE
                    )
                )
            )
            if (length(unique(total_po_adj@sam_data$SubGroup_IsolationSource)) > 2) {
                w <- t1$res_pair
                ref_group <- sort(unique(total_po_adj@sam_data$SubGroup_IsolationSource))[1]
                prefixes <- c("lfc", "se", "W", "p", "q", "diff", "passed_ss")
                for (pref in prefixes) {
                    single_cols <- grep(paste0("^", pref, "_SubGroup_IsolationSource(?!.*SubGroup_IsolationSource)"), names(w), perl = TRUE, value = TRUE)
                    for (col in single_cols) {
                        new_name <- paste0(col, "_SubGroup_IsolationSource", ref_group)
                        names(w)[names(w) == col] <- new_name
                    }
                }
                res_pair <- w |>
                    dplyr::select(taxon, contains("lfc"), contains ("q_"), contains("diff")) |>
                    filter(if_any(contains("diff"), ~ .x == TRUE))
                res_long <- res_pair |>
                    pivot_longer(cols = -taxon,names_to = c(".value", "comparison"),
                                names_pattern = "(lfc|q|diff)_(.*)") |>
                    mutate(
                        comparison = str_remove_all(comparison, "SubGroup_IsolationSource")
                    ) |>
                    filter(q <= alpha)
                suppressWarnings(suppressMessages(p <- res_long))
                trim_lda <- p[, c("taxon", "comparison", "lfc", "q")]
            } else {
#                 w <- t1$res
#                 res_long <- w |>
#                     filter(P.adj <= alpha)
#                 res_long <- as.data.frame(subset(res_long, Factors != "(Intercept)"))
#                 comparison_label <- sort(unique(total_meco_object_adj$sample_table$SubGroup_IsolationSource), decreasing = TRUE)
#                 if (nrow(res_long) > 0)
#                     res_long[, "Factors"] <- rep(paste0(comparison_label[1], "_", comparison_label[2]), nrow(res_long))
#                 suppressWarnings(suppressMessages(p <- res_long))
                w <- t1$res
                ref_group <- sort(unique(total_po_adj@sam_data$SubGroup_IsolationSource))[1]
                prefixes <- c("lfc", "se", "W", "p", "q", "diff", "passed_ss")
                for (pref in prefixes) {
                  single_cols <- grep(paste0("^", pref, "_SubGroup_IsolationSource(?!.*SubGroup_IsolationSource)"), names(w), perl = TRUE, value = TRUE)
                  for (col in single_cols) {
                    new_name <- paste0(col, "_SubGroup_IsolationSource", ref_group)
                    names(w)[names(w) == col] <- new_name
                  }
                }
                res_pair <- w |>
                  dplyr::select(taxon, contains("lfc"), contains ("q_"), contains("diff")) |>
                  filter(if_any(contains("diff"), ~ .x == TRUE))
                res_long <- res_pair |>
                  pivot_longer(cols = -taxon,names_to = c(".value", "comparison"),
                               names_pattern = "(lfc|q|diff)_(.*)") |>
                  mutate(
                    comparison = str_remove_all(comparison, "SubGroup_IsolationSource")
                  ) |>
                  filter(q <= alpha)
                res_long <- as.data.frame(subset(res_long, comparison != "(Intercept)"))
#                 clean_taxon <- function(taxon) {
#                 # Remove numeric suffixes, but keep taxons with names (e.g., g__Moraxella)
#                 gsub("__\\d+", "", taxon)
#                 }
#                 # Apply the cleaning function
#                 res_long$taxon <- sapply(res_long$taxon, clean_taxon)
                suppressWarnings(suppressMessages(p <- res_long))
                trim_lda <- p[, c("taxon", "comparison", "lfc", "q")]
            }
#             if (length(unique(total_meco_object_adj$sample_table$SubGroup_IsolationSource)) <= 2) {
#                 res_long <- as.data.frame(subset(res_long, comparison != "(Intercept)"))
#                 str(res_long)
#                 print(as.data.frame(res_long))
#                 comparison_label <- sort(unique(total_meco_object_adj$sample_table$SubGroup_IsolationSource), decreasing = TRUE)
#                 res_long$comparison <- rep(paste0(comparison_label[1], "_", comparison_label[2]), nrow(res_long))
#             }
#             suppressWarnings(suppressMessages(p <- res_long))
#             trim_lda <- p[, c("taxon", "comparison", "lfc", "q")]
            colnames(trim_lda) <- c("Taxa", "Group", "Value", "P.adj")
            if (nrow(trim_lda) > 0) {
                trim_lda[, "Significance"] <- ""
                for (i in 1:nrow(trim_lda)) {
                    taxa_splits <- strsplit(as.character(trim_lda[i, "Taxa"]), "\\|")[[1]]
                    trim_lda[i, "Taxa"] <- taxa_splits[length(taxa_splits)]
                }
            }
            trim_lda <- trim_lda[abs(trim_lda$Value) > threshold & (trim_lda$Taxa) != "s__" & (trim_lda$Taxa) != "g__" & (trim_lda$Taxa) != "f__" & (trim_lda$Taxa) != "o__", ]
        }

#         else {
#             suppressWarnings(suppressMessages(p <- t1$plot_diff_bar(use_number = 1:200)))
#             trim_lda <- p$data[, c("Taxa", "Group", "logFC", "P.unadj", "P.adj", "Significance")]
#             colnames(trim_lda) <- c("Taxa", "Group", "Value", "P.unadj", "P.adj", "Significance")
#             trim_lda <- trim_lda[abs(trim_lda$Value) > threshold & (trim_lda$Taxa) != "s__" & (trim_lda$Taxa) != "g__" & (trim_lda$Taxa) != "f__" & (trim_lda$Taxa) != "o__", ]
#         }

        p_adjust_json <- paste0("\"p_adjust\":\"", p_adjust_method, "\"")
        taxa_json <- "\"taxa\":["
        subgroup_json <- "\"subgroup\":["
        abundance_json <- "\"value\":["
        pval_json <- "\"pval\":["
        padj_json <- "\"padj\":["
        significance_json <- "\"significance\":["
        i <- 1
        while (i <= nrow(trim_lda)) {
            if (i == nrow(trim_lda)) {
                taxa_json <- paste0(taxa_json, "\"", trim_lda[i, "Taxa"], "\"")
                subgroup_json <- paste0(subgroup_json, "\"", trim_lda[i, "Group"], "\"")
                abundance_json <- paste0(abundance_json, trim_lda[i, "Value"])
                pval_json <- paste0(pval_json, "\"\"")
                padj_json <- paste0(padj_json, trim_lda[i, "P.adj"])
                significance_json <- paste0(significance_json, "\"", trim_lda[i, "Significance"], "\"")
            } else {
                taxa_json <- paste0(taxa_json, "\"", trim_lda[i, "Taxa"], "\",")
                subgroup_json <- paste0(subgroup_json, "\"", trim_lda[i, "Group"], "\",")
                abundance_json <- paste0(abundance_json, trim_lda[i, "Value"], ",")
                pval_json <- paste0(pval_json, "\"\"", ",")
                padj_json <- paste0(padj_json, trim_lda[i, "P.adj"], ",")
                significance_json <- paste0(significance_json, "\"", trim_lda[i, "Significance"], "\",")
            }
            i <- i + 1
        }
        taxa_json <- paste0(taxa_json, "]")
        subgroup_json <- paste0(subgroup_json, "]")
        abundance_json <- paste0(abundance_json, "]")
        pval_json <- paste0(pval_json, "]")
        padj_json <- paste0(padj_json, "]")
        significance_json <- paste0(significance_json, "]")
        merged_lda_out_json <- paste0("{", p_adjust_json, ",", taxa_json, ",", subgroup_json, ",", abundance_json, ",", pval_json, ",", padj_json, ",", significance_json, "}")


        out_json <- paste0("{\"lda\":", lda_out_json, ",\"merged_lda\":", merged_lda_out_json, "}")
        cat(out_json)
    },
    error = function(condition) {
        lda_out_json <- "{\"p_adjust\":\"\",\"taxa\":[],\"subgroup\":[],\"value\":[],\"pval\":[],\"padj\":[],\"significance\":[]}"
        merged_lda_out_json <- "{\"p_adjust\":\"\",\"taxa\":[],\"subgroup\":[],\"value\":[],\"pval\":[],\"padj\":[],\"significance\":[]}"
        out_json <- paste0("{\"lda\":", lda_out_json, ",\"merged_lda\":", merged_lda_out_json, "}")
        cat(out_json)
    }
)
