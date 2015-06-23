#!/usr/bin/Rscript
#-*- coding:utf8 -*-
options(width=1000)
.libPaths("/home/ubuntu/R/x86_64-unknown-linux-gnu-library/3.0/")

library(cBioFetchR)

options("max.print"=1000)
conn = cBioConnect()
listStudies(conn)[1:2]
