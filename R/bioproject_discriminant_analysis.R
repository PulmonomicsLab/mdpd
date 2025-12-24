args <- commandArgs(trailingOnly=TRUE)
bioprojectID <- args[1]
assayType <- args[2]
isolationSource <- args[3]
method <- args[4]
alpha <- as.double(args[5])
p_adjust_method <- args[6]
filter_thres <- as.double(args[7])
taxa_level <- args[8]
threshold <- as.double(args[9])

set.seed(42)

inputPath <- "input/biom/"
libraryPath <- "Rlibs/"

.libPaths(c(libraryPath, .libPaths()))
library(phyloseq, quietly=TRUE)
library(microeco, quietly=TRUE)
library(file2meco, quietly=TRUE)
suppressMessages(library(ANCOMBC, quietly = TRUE))
library(stringr, quietly=TRUE)
suppressMessages(library(dplyr, quietly=TRUE))
suppressMessages(library(tidyr, quietly=TRUE))
suppressMessages(library(tidyverse, quietly=TRUE))

# Function to run LINDA with a chosen reference group
run_linda_ref <- function(ref, grp) {
    suppressMessages(meco_object$sample_table$SubGroup <- relevel(meco_object$sample_table$SubGroup, ref))
    suppressWarnings(
        suppressMessages(
            t1 <- trans_diff$new(
                dataset = meco_object,
                method = method,
                alpha = alpha,
                group = "SubGroup",
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

tryCatch(
    {
        # Initialize params
        if (assayType == "WMS") {
            rel_abund <- 0.0001
            freq <- 0.05
            pollution_filters = "Chordata"
        } else {
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

        meco_object$sample_table <- subset(meco_object$sample_table, (IsolationSource == isolationSource))
        suppressMessages(meco_object$tidy_dataset())

#         # LDA plot Genus
#         suppressWarnings(suppressMessages(t1 <- trans_diff$new(dataset = meco_object, method = method, alpha = alpha, lefse_subgroup = NULL, group = "SubGroup", filter_thres = filter_thres, taxa_level = taxa_level, p_adjust_method = p_adjust_method)))
#         if (method == "lefse") {
#             p <- t1$plot_diff_bar(threshold = threshold, use_number = 1:200)
#             trim_lda <- p$data[, c("Taxa", "Group", "Value", "P.unadj", "P.adj", "Significance")]
#         } else {
#             suppressWarnings(suppressMessages(p <- t1$plot_diff_bar(use_number = 1:200)))
#             trim_lda <- p$data[, c("Taxa", "Group", "logFC", "P.unadj", "P.adj", "Significance")]
#             colnames(trim_lda) <- c("Taxa", "Group", "Value", "P.unadj", "P.adj", "Significance")
#             trim_lda <- trim_lda[abs(trim_lda$Value) > threshold & (trim_lda$Taxa) != "s__" & (trim_lda$Taxa) != "g__" & (trim_lda$Taxa) != "f__" & (trim_lda$Taxa) != "o__", ]
#         }

        if (method == "lefse") {
            suppressWarnings(
                suppressMessages(
                    t1 <- trans_diff$new(
                        dataset = meco_object,
                        method = method,
                        alpha = alpha,
                        lefse_subgroup = NULL,
                        group = "SubGroup",
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
                        dataset = meco_object,
                        method = method,
                        alpha = alpha,
                        group = "SubGroup",
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
            suppressMessages(meco_object$sample_table$SubGroup <- factor(meco_object$sample_table$SubGroup))
            # Detect all group levels automatically
            suppressMessages(group_levels <- levels(meco_object$sample_table$SubGroup))
            # Run LINDA for all possible reference groups
            suppressMessages(res_list <- lapply(group_levels, run_linda_ref))
            # Combine all results into one data frame
            suppressMessages(all_results <- bind_rows(res_list))
            # Normalize comparison names to remove reversed duplicates
            suppressMessages(all_results_clean <- all_results %>%
                tidyr::separate(Comparison, into = c("Group2", "Group1"), sep = " - ") %>%
                rowwise() %>%
                mutate(
                    # Create a normalized identifier
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
            trim_lda <- trim_lda[abs(trim_lda$Value) > threshold & (trim_lda$Taxa) != "s__" & (trim_lda$Taxa) != "s_" & (trim_lda$Taxa) != "g__" & (trim_lda$Taxa) != "g_" & (trim_lda$Taxa) != "f__" & (trim_lda$Taxa) != "f_" & (trim_lda$Taxa) != "o__" & (trim_lda$Taxa) != "o__", ]
        } else if (method == "ancombc2") {
          suppressWarnings(suppressMessages(po <- meco2phyloseq(meco_object)))
            suppressWarnings(
                suppressMessages(
                    # t1 <- trans_diff$new(
                    #     dataset = meco_object,
                    #     method = method,
                    #     alpha = alpha,
                    #     group = "SubGroup",
                    #     filter_thres = filter_thres,
                    #     taxa_level = taxa_level,
                    #     p_adjust_method = p_adjust_method,
                    #     pairwise = TRUE
                  # )
                    t1 <- ancombc2(
                        po,
                        tax_level = taxa_level,
                        fix_formula = "SubGroup",
                        group = "SubGroup",
                        alpha = alpha,
                        p_adj_method = p_adjust_method,
                        pairwise = TRUE,
                        n_cl = 16,
                        prv_cut = filter_thres,
                        verbose = FALSE
                    )
                )
            )
            if (length(unique(po@sam_data$SubGroup)) > 2) {
                w <- t1$res_pair
                ref_group <- sort(unique(po@sam_data$SubGroup))[1]
                prefixes <- c("lfc", "se", "W", "p", "q", "diff", "passed_ss")
                for (pref in prefixes) {
                    single_cols <- grep(paste0("^", pref, "_SubGroup(?!.*SubGroup)"), names(w), perl = TRUE, value = TRUE)
                    for (col in single_cols) {
                        new_name <- paste0(col, "_SubGroup", ref_group)
                        names(w)[names(w) == col] <- new_name
                    }
                }
                res_pair <- w |>
                    dplyr::select(taxon, contains("lfc"), contains ("q_"), contains("diff")) |>
                    filter(if_any(contains("diff"), ~ .x == TRUE))
#                 print(res_pair)
                res_long <- res_pair |>
                    pivot_longer(cols = -taxon,names_to = c(".value", "comparison"),
                                names_pattern = "(lfc|q|diff)_(.*)") |>
                    mutate(
                        comparison = str_remove_all(comparison, "SubGroup")
                    ) |>
                    filter(q <= alpha)
                clean_taxon <- function(taxon) {
                    # Remove numeric suffixes, but keep taxons with names (e.g., g__Moraxella)
                    gsub("__\\d+", "", taxon)
                }
                # Apply the cleaning function
                res_long$taxon <- sapply(res_long$taxon, clean_taxon)
                suppressWarnings(suppressMessages(p <- res_long))
                trim_lda <- p[, c("taxon", "comparison", "lfc", "q")]
            }
            else {
                # w <- t1$res_diff
                # res_long <- w |>
                #     filter(P.adj <= alpha)
                # res_long <- as.data.frame(subset(res_long, Factors != "(Intercept)"))
                # comparison_label <- sort(unique(meco_object$sample_table$SubGroup), decreasing = TRUE)
                # if (nrow(res_long) > 0)
                #     res_long[, "Factors"] <- rep(paste0(comparison_label[1], "_", comparison_label[2]), nrow(res_long))
                # suppressWarnings(suppressMessages(p <- res_long))
                # trim_lda <- p[, c("Taxa", "Factors", "lfc", "P.adj")]
                w <- t1$res
                ref_group <- sort(unique(po@sam_data$SubGroup))[1]
                prefixes <- c("lfc", "se", "W", "p", "q", "diff", "passed_ss")
                for (pref in prefixes) {
                  single_cols <- grep(paste0("^", pref, "_SubGroup(?!.*SubGroup)"), names(w), perl = TRUE, value = TRUE)
                  for (col in single_cols) {
                    new_name <- paste0(col, "_SubGroup", ref_group)
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
                    comparison = str_remove_all(comparison, "SubGroup")
                  ) |>
                  filter(q <= alpha)
                res_long <- as.data.frame(subset(res_long, comparison != "(Intercept)"))
                clean_taxon <- function(taxon) {
                # Remove numeric suffixes, but keep taxons with names (e.g., g__Moraxella)
                gsub("__\\d+", "", taxon)
                }
                # Apply the cleaning function
                res_long$taxon <- sapply(res_long$taxon, clean_taxon)
                suppressWarnings(suppressMessages(p <- res_long))
                trim_lda <- p[, c("taxon", "comparison", "lfc", "q")]
              }
              colnames(trim_lda) <- c("Taxa", "Group", "Value", "P.adj")
              if (nrow(trim_lda) > 0) {
                  trim_lda[, "Significance"] <- ""
                  for (i in 1:nrow(trim_lda)) {
                      taxa_splits <- strsplit(as.character(trim_lda[i, "Taxa"]), "\\|")[[1]]
                      trim_lda[i, "Taxa"] <- taxa_splits[length(taxa_splits)]
                  }
            }
            trim_lda <- trim_lda[abs(trim_lda$Value) > threshold & (trim_lda$Taxa) != "s__" & (trim_lda$Taxa) != "s_" & (trim_lda$Taxa) != "g__" & (trim_lda$Taxa) != "g_" & (trim_lda$Taxa) != "f__" & (trim_lda$Taxa) != "f_" & (trim_lda$Taxa) != "o__" & (trim_lda$Taxa) != "o__", ]
        }

        p_adjust_json <- paste0("\"p_adjust\":\"", p_adjust_method, "\"")
        taxa_json <- "\"taxa\":["
        subgroup_json <- "\"subgroup\":["
        abundance_json <- "\"value\":["
#         pval_json <- "\"pval\":["
        padj_json <- "\"padj\":["
        significance_json <- "\"significance\":["
        i <- 1
        while (i <= nrow(trim_lda)) {
            if (i == nrow(trim_lda)) {
                taxa_json <- paste0(taxa_json, "\"", trim_lda[i, "Taxa"], "\"")
                subgroup_json <- paste0(subgroup_json, "\"", trim_lda[i, "Group"], "\"")
                abundance_json <- paste0(abundance_json, trim_lda[i, "Value"])
#                 pval_json <- paste0(pval_json, trim_lda[i, "P.unadj"])
                padj_json <- paste0(padj_json, trim_lda[i, "P.adj"])
                significance_json <- paste0(significance_json, "\"", trim_lda[i, "Significance"], "\"")
            } else {
                taxa_json <- paste0(taxa_json, "\"", trim_lda[i, "Taxa"], "\",")
                subgroup_json <- paste0(subgroup_json, "\"", trim_lda[i, "Group"], "\",")
                abundance_json <- paste0(abundance_json, trim_lda[i, "Value"], ",")
#                 pval_json <- paste0(pval_json, trim_lda[i, "P.unadj"], ",")
                padj_json <- paste0(padj_json, trim_lda[i, "P.adj"], ",")
                significance_json <- paste0(significance_json, "\"", trim_lda[i, "Significance"], "\",")
            }
            i <- i + 1
        }
        taxa_json <- paste0(taxa_json, "]")
        subgroup_json <- paste0(subgroup_json, "]")
        abundance_json <- paste0(abundance_json, "]")
#         pval_json <- paste0(pval_json, "]")
        padj_json <- paste0(padj_json, "]")
        significance_json <- paste0(significance_json, "]")

#         out_json <- paste0("{", p_adjust_json, ",", taxa_json, ",", subgroup_json, ",", abundance_json, ",", pval_json, ",", padj_json, ",", significance_json, "}")
        out_json <- paste0("{", p_adjust_json, ",", taxa_json, ",", subgroup_json, ",", abundance_json, ",", padj_json, ",", significance_json, "}")
        cat(out_json)
    },
    error = function(condition) {
        out_json <- "{\"p_adjust\":\"\",\"taxa\":[],\"subgroup\":[],\"value\":[],\"pval\":[],\"padj\":[],\"significance\":[]}"
        cat(out_json)
    }
)
