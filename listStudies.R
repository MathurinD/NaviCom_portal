#!/usr/bin/Rscript
#-*- coding:utf8 -*-
options(width=1000)
options("max.print"=1000)
.libPaths("/home/ubuntu/R/x86_64-unknown-linux-gnu-library/3.0/")

library(cBioFetchR)

conn = cBioConnect()
listStudies(conn)[1:2]
