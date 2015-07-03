#!/bioinfo/local/build/R/R-3.1.0/bin/Rscript
#-*- coding:utf8 -*-
options(width=1000)
options("max.print"=1000)
.libPaths("/bioinfo/pipelines/navicom/dev/html/lib/")

library(cBioFetchR)

conn = cBioConnect()
listStudies(conn)[1:2]
