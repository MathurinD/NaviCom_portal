#!/usr/bin/Rscript
#-*- coding:utf8 -*-
options(width=1000)
options("max.print"=1000)
.libPaths("/home/ubuntu/R/x86_64-unknown-linux-gnu-library/3.0/")

library(cBioFetchR)

arg = commandArgs(trailingOnly=T)

study_id = arg[1]
nc = cBioNCviz(study_id, genes_list="./acsn_v1.1.gmt")
fname = saveData(nc, suffix=arg[2])
print(paste0("FNAME: ", fname))
