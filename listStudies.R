library(cBioFetchR)

options("max.print"=1000)
conn = cBioConnect()
listStudies(conn)[1:2]
