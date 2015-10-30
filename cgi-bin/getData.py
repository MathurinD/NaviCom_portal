#!/usr/bin/python3
#-*- coding:utf8 -*-

import sys
sys.path.append("/bioinfo/local/build/numpy_python3/lib/python3.1/site-packages/") # numpy
sys.path.append("/bioinfo/pipelines/navicom/dev/html/lib/") # navicell
import cgi
import os
import re
import time
import subprocess
import cgitb
cgitb.enable()
from navicom import *
from helper_cgi import *

form = cgi.FieldStorage()
#print_headers()

study_id = getFormValue(form, "study_id")

if ('url' in form):
    url = form["url"].value
    url_dir = processURL(url)
    rel_dir = ".." + url_dir # Relative path for the cgis
else:
    error("'url' field is not specified\n")

#log("Start")
study = os.popen("ls " + rel_dir + " | grep 'id=" + study_id + "\.txt'").readlines()
if (len(study) >= 1):
    study = study[0].strip()
else:
    log("Downloading data for id " + study_id + ", in repository " + url_dir)
    # Generate gmt file with the genes on the map
    gmt = os.popen("ls " + rel_dir + "* | grep " + url_dir + ".gmt").readlines()
    if (len(gmt) >= 1):
        gmt = gmt[0].strip()
    else:
        if ('id' in form):
            session_id = form["id"].value
        else:
            error("'id' field is not specified")
        nc = NaviCom()
        attachNaviCell(nc, url, session_id)
        nc._nv.noticeMessage('', 'Loading', 'NaviCom is using the map to download data<br/>This window will close automatically once the task has been completed', position='middle')
        nc._nv.flush()
        gmt = rel_dir[:-1] + ".gmt"
        with open(gmt, "w") as ff:
            genes = nc._nv.getHugoList()
            if (len(genes) < 1):
                return_error("Invalid Map (cannot get a list of HUGO names from the map)")
            ff.write( "ALL\tna\t" + '\t'.join(genes) )
        if not os.path.exists(rel_dir):
            os.makedirs(rel_dir)
        nc._nv.noticeClose('')
        nc._nv.flush()
    log("gmt: " + str(gmt))
    
    with open(os.devnull, "a") as devnull:
        errors = str( subprocess.Popen(["./getData.R", study_id, "id="+study_id, url_dir, gmt], stdout=devnull, stderr=subprocess.PIPE).communicate() )
    log(errors)
    study = os.popen("ls " + rel_dir + " | grep 'id=" + study_id + "\.txt'").readlines()
    log(study_id + " " + str(study))
    study = study[0].strip()

log("Data generated")

print_dl_headers(study)
#print("FNAME: /scratch/navicom/" + study)
print("FNAME: " + url_dir + study)

