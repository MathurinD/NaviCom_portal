#!/bioinfo/local/build/R/R-3.1.0/bin/Rscript
#-*- coding:utf8 -*-
.libPaths(c(.libPaths(), "/bioinfo/pipelines/navicom/dev/html/lib/"))

library(cBioFetchR)
target_rep = "/scratch/navicom/acsn/"

conn = cBioConnect()
studies = listStudies(conn)
scratch = dir(target_rep)
ff = file("/scratch/navicom/all_studies.txt", "w")
gene_list = "/bioinfo/pipelines/navicom/dev/html/cgi-bin/acsn_v1.1.gmt"

for (ii in 1:nrow(studies)) {
    suffix = paste0("id=", studies[ii, 1])
    dfile = grepl(paste0(suffix, ".txt"), scratch)
    print(paste(studies[ii,2], "(", studies[ii,1], ")"))
    worked = TRUE
    if (!any( dfile )) {
        tryCatch({
            nc = cBioNCviz(studies[ii, 1], genes_list=gene_list)
            fname = saveData(nc, path=target_rep, suffix=suffix)
        }, error=function(e) {
            tryCatch({ # If the fast version failed, try the slow one
                .GlobalEnv$nc = cBioNCviz(studies[ii, 1], genes_list=gene_list, method="genes")
                .GlobalEnv$fname = saveData(nc, path=target_rep, suffix=suffix)
            }, error=function(e) {
                warnings(paste0("Study ", studies[ii, 1], " was not downloaded properly"))
                .GlobalEnv$worked = FALSE
            })
        })
    } else {
        fname = scratch[which(dfile)[1]]
        nc = importNCviz(paste0(target_rep, fname))
    }
    if (worked) {
        print(fname)
        writeLines( paste( fname, length(nc@nc_data), ncol(nc@nc_data[[1]]), paste(names(nc@nc_data), collapse=" ") ), ff)
        writeLines( paste( colnames(nc@nc_data[[1]]), collapse=" " ), ff )
    }
}
close(ff)

