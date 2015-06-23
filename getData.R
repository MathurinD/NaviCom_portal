#!/usr/bin/Rscript
#-*- coding:utf8 -*-
options(width=1000)
.libPaths("/home/ubuntu/R/x86_64-unknown-linux-gnu-library/3.0/")

library(cBioFetchR)

study_id = commandArgs(trailingOnly=T)
nc = cBioNCviz(study_id, genes_list="./acsn_v1.1.gmt")

fname = saveData(nc)
print(paste0("FNAME: ", fname))
