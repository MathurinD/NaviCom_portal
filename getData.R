library(cBioFetchR)

study_id = commandArgs(trailingOnly=T)
nc = cBioNCviz(study_id, genes_list="./acsn_v1.1.gmt")

fname = saveData(nc)
print(paste0("FNAME: ", fname))
