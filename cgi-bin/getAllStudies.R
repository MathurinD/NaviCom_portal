#!/bioinfo/local/build/R/R-3.1.0/bin/Rscript
#-*- coding:utf8 -*-
.libPaths("/bioinfo/pipelines/navicom/dev/html/lib/")

library(cBioFetchR)
target_rep = "/scratch/navicom/acsn/"

conn = cBioConnect()
studies = listStudies(conn)
scratch = dir(target_rep)
ff = file("/scratch/navicom/all_studies.txt", "w")

for (ii in 1:nrow(studies)) {
    suffix = paste0("id=", studies[ii, 1])
    dfile = grepl(paste0(suffix, ".txt"), scratch)
    print(paste(studies[ii,2], "(", studies[ii,1], ")"))
    if (!any( dfile )) {
        tryCatch({
            nc = cBioNCviz(studies[ii, 1], genes_list="/bioinfo/pipelines/navicom/dev/html/cgi-bin/acsn_v1.1.gmt")
            fname = saveData(nc, path=target_rep, suffix=suffix)
        }, error=function(e) {
            warnings(paste0("Study ", studies[ii, 1], " was not downloaded properly"))
        })
    } else {
        fname = scratch[which(dfile)[1]]
        nc = importNCviz(paste0(target_rep, fname))
    }
    writeLines( paste( fname, length(nc@nc_data), ncol(nc@nc_data[[1]]), paste(names(nc@nc_data), collapse=" ") ), ff)
}
close(ff)

