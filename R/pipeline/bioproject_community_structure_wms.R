#This Rscript allows to obtain the microbial community structure from WMS BioProjects
#Using gblm method
input_folder <- "/input_path/"
output_folder <- "/output_path/"
#get the list of bioprojects with column 1 = bioprojectID, column 2 = assayType, column 3 = isolationSource
csv_file <- read.csv("list_of_bioprjoects_wms.csv", header = FALSE, sep = ",")
print(csv_file)

for (row in 1:nrow(csv_file)) {
   tryCatch (
     {
      bioprojectID <- csv_file[row, 1]
      assayType <- csv_file[row, 2]
      isolationSource <- csv_file[row, 3]

      set.seed(42)

      library(phyloseq, quietly=TRUE)
      library(microeco, quietly=TRUE)
      library(file2meco, quietly=TRUE)
      library(mboost, quietly=TRUE)
      library(boot, quietly=TRUE)

      # Read biom RDS
      ps <- readRDS(paste0(input_folder, bioprojectID, "_WMS_ps_object.rds"))
      ps <- subset_taxa(ps, Phylum != "Chordata")
      # Create phyloseq object to meco object
      meco_object <- suppressMessages(phyloseq2meco(ps))
      meco_object$tidy_dataset()

      # Filter pollution
      suppressMessages(meco_object$filter_pollution(taxa = c("chordata")))
      meco_object$tidy_dataset()

      # Filter based on abundance and detection
      suppressMessages(meco_object$filter_taxa(rel_abund = 0.0001, freq = 0.05))
      meco_object$tidy_dataset()
      meco_object$sample_table <- subset(meco_object$sample_table, (IsolationSource == isolationSource))
      meco_object$tidy_dataset()

      meco_object$cal_abund(select_cols = "Species")
      features <- meco_object$taxa_abund$Species
      features <- t(features)
      features <- as.data.frame(features)
      features$SubGroup <- meco_object$sample_table$SubGroup
      print(colnames(features))

      ifelse(colnames(features) == "s__",
      subset(features, select=-c(s__)),
      features
      )
      columns <- as.array(colnames(features))
      for (i in 1:length(columns)) {
        splits <- strsplit(columns[i], "\\|")
        columns[i] <- splits[[1]][length(splits[[1]])]
        columns[i] <- gsub(" ", "_", columns[i])
        columns[i] <- gsub("-", "_", columns[i])
        columns[i] <- gsub("\\[", "_", columns[i])
        columns[i] <- gsub("\\]", "_", columns[i])
        columns[i] <- gsub("\\(", "_", columns[i])
        columns[i] <- gsub("\\)", "", columns[i])
        columns[i] <- gsub("\\{", "_", columns[i])
        columns[i] <- gsub("\\}", "_", columns[i])
        columns[i] <- gsub("/", "_", columns[i])
      }
      colnames(features) <- columns

      subgroups <- unique(features$SubGroup)
      ntaxa <- ncol(features) - 1
      write.csv(colnames(features), file = "file.csv")

      edgeList <- data.frame(SubGroup=c(), Taxa1=c(), Taxa2=c(), Score=c())
      count <- 1
      for (s in 1:length(subgroups)) {
         tryCatch(
           {
            data <- subset(features, SubGroup == subgroups[s])
            data <- subset(data, select=-SubGroup)
            cr_mat <- cor(data,method = "spearman") #Compute Spearman Correlation
            cr_mat[is.na(cr_mat)] <- 0  # Assign 0 to NA values(NA due to zero STD)

            #Adjacency matrix and p-value matrix creation
            m <- matrix(0,ncol=dim(cr_mat)[1],nrow=dim(cr_mat)[2])
            p_m <- matrix(2,ncol=dim(cr_mat)[1],nrow=dim(cr_mat)[2])
            m <- data.frame(m,row.names = colnames(data))
            p_m <- data.frame(p_m,row.names = colnames(data))
            colnames(m) <- colnames(data)
            colnames(p_m) <- colnames(data)


            r2 <- function(model,col=i,data=data) {
              sse <- sum((predict(model,data)-data[[col]])^2)
              tss <- sum((data[[col]]-mean(data[[col]]))^2)
              error <- 1-(sse/tss)
              return(error)
            }

            for (i in 1:ntaxa){
              x_nam=rownames(cr_mat)[i]
              ind1=abs(cr_mat[i,]) > 0.05 & abs(cr_mat[i,]) != 1
              cool=which(ind1, arr.ind = T)
              y_nam=rownames(as.data.frame(cool)) #Column names with correlation >0.05 and != 1
              if(identical(y_nam,character(0)) == TRUE){next}
              print(x_nam) #Row name

              #Formula
              print(paste(x_nam,paste(y_nam,collapse = "+"),sep="~"))
              form=as.formula(paste(x_nam,paste(y_nam,collapse = "+"),sep="~"))

              #GLMBoosting and Model Tuning, Depends on randomness
              model1<-glmboost(form,data=data,family = Gaussian(),
                              center=TRUE,control = boost_control(mstop=200,nu=0.05,trace=TRUE))
              #Induces randomness, can loop and take the nearest average integer
              f<-cv(model.weights(model1),type="kfold",B=10)
              cvm<-cvrisk(model1,folds=f,mc.cores=42)
              opt_m<-mstop(cvm)
              if(opt_m==0){opt_m=1}

              #Choosing the optimal model
              model1[opt_m]
              error=r2(model1,col=i,data=data)
              if (error<0.5){next}
              wghts<-coef(model1,which="")
              x<-t(as.data.frame(wghts[-1]))
              row.names(x)<-x_nam

              #Appending the coefficient matrix to adjacency matrix
              for(cl in colnames(x)){
                m[x_nam,cl]<-x[x_nam,cl]
              }

              #Bootstrap distribution
              #------------Using boot-----------------
              library(boot)
              boot.stat<-function(data,indices,m_stop,form,x_nam){
                data<-data[indices,]
                mod<-glmboost(form,data=data,family = Gaussian(),
                              center=FALSE,control = boost_control(mstop=m_stop,nu=0.05,trace=TRUE))
                wghts<-coef(mod,which="")
                x<-t(as.data.frame(wghts[-1]))
                row.names(x)<-x_nam
                return(x)
              }

              model.boot<-boot(data,boot.stat,100,m_stop=opt_m,form=form,x_nam=x_nam)
              #-----------Permutation with renormalization-------------
              #copy of the data
              data_p=data
              out_comb<-x

              #permutation
              counter=0
              while (counter<100){
                data_p[[x_nam]]<-sample(data_p[[x_nam]])
                #renormalization
                data_p=(data_p/rowSums(data_p))
                out<-boot.stat(data_p,indices = 1:dim(data)[1],m_stop=opt_m,form=form,x_nam=x_nam)
                out_comb=rbind(out_comb,out)
                counter = counter + 1
              }
              out_comb<-out_comb[-1,]

              #Comparing two distributions
              p_test<-c()
              for (i in 1:dim(out_comb)[2]){
                p=wilcox.test(model.boot$t[,i],out_comb[,i],alternative = "two.sided",paired = FALSE)$p.value
                p_test<-c(p_test,p)
              }

              #correction of multiple comparision
              p_test<-p.adjust(p_test,method = "fdr")
              for (i in 1:dim(out_comb)[2]){
                p_m[x_nam,colnames(out_comb)[i]]<-p_test[i]
              }
            }
            count <- count+1
            for (p in 1:nrow(m)) {
              for (q in 1:ncol(m)) {
                if (m[p, q] != 0) {
                  entryPoint <- nrow(edgeList)+1
                  edgeList[entryPoint, "SubGroup"] <- subgroups[s]
                  edgeList[entryPoint, "Taxa1"] <- rownames(m)[p]
                  edgeList[entryPoint, "Taxa2"] <- colnames(m)[q]
                  edgeList[entryPoint, "Score"] <- m[p, q]
                }
              }
            }
           },
           error = function(condition) {}
         )
      }
      warnings()
      print(edgeList)
      write.table(edgeList, paste0(output_folder, bioprojectID, "_", assayType, "_", isolationSource, ".csv"), sep="\t", row.names = FALSE)
     },
     error = function(condition) {}
   )

}
