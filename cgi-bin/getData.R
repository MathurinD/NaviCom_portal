#!/bioinfo/local/build/R/R-3.1.0/bin/Rscript
#-*- coding:utf8 -*-
options(width=1000)
options("max.print"=1000)
.libPaths("/bioinfo/pipelines/navicom/dev/html/lib/")

library(cBioFetchR)

arg = commandArgs(trailingOnly=T)
target_rep = "/scratch/navicom/"
#target_rep = "/bioinfo/pipelines/navicom/dev/html/share/"

study_id = arg[1]
#if (length(arg) >= 2) {
        #nc = cBioNCviz(study_id, genes_list=arg[2])
#} else {
        #nc = cBioNCviz(study_id, genes_list="/bioinfo/pipelines/navicom/dev/html/cgi-bin/acsn_v1.1.gmt")
#}
if (length(arg) >= 3) {
        target_rep = arg[3]
        dir.create(target_rep)
}

nc = cBioNCviz(study_id, genes_list="/bioinfo/pipelines/navicom/dev/html/cgi-bin/acsn_v1.1.gmt")

if (length(arg) >= 2) {
        fname = saveData(nc, path=target_rep, suffix=arg[2])
} else {
        fname = saveData(nc, path=target_rep)
}
print(paste0("FNAME: ", fname))
