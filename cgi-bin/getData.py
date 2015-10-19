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
from helper_cgi import *

form = cgi.FieldStorage()
#print_headers()

if ("study_selection" in form):
    study_id = form['study_selection'].value
else:
    error("'study_selection' field is not specified\n")

if ('url' in form):
    url = form["url"].value
    url_dir = processURL(url)
    rel_dir = ".." + url_dir # Relative path for the cgis
else:
    error("'url' field is not specified\n")

log("Start")
study = os.popen("ls " + rel_dir + " | grep 'id=" + study_id + "\.txt'").readlines()
if (len(study) >= 1):
    study = study[0].strip()
else:
    log("Downloading data for id " + study_id + ", in repository " + url_dir)
    subprocess.Popen(["./getData.R", study_id, "id="+study_id, url_dir], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    study = os.popen("ls " + rel_dir + " | grep 'id=" + study_id + "\.txt'").readlines()
    log(study_id + " " + str(study))
    study = study[0].strip()

log("Data generated")

print_dl_headers(study)
#print("FNAME: /scratch/navicom/" + study)
print("FNAME: " + url_dir + study)

