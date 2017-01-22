#!/bioinfo/local/build/R/R-3.1.0/bin/Rscript
#-*- coding:utf8 -*-
.libPaths("/bioinfo/pipelines/navicom/dev/html/lib/")
library(cBioFetchR)

arg = commandArgs(trailingOnly=T)
if (length(arg) < 2) {
        stop("patient_data requires a study id and a patient id")
}
target_rep = "/scratch/navicom/"
if (length(arg) >= 3) {
        target_rep = arg[3]
        dir.create(target_rep)
}

obj = importNCViz(arg[1])
for (method in names(obj@nc_data)) {
obj@nc_data = obj@nc_data[,arg[2], drop=FALSE]
}
obj@annotations = obj@annotations[arg[2],,drop=FALSE]
obj@cell_type = paste0(obj@cell_type, "_", arg[2])
fname = saveData(obj, path=target_rep)
print(paste0("FNAME: ", fname))
