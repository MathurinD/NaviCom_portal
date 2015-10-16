#!/usr/bin/python3
#-*- coding:utf8 -*-

import sys
sys.path.append("/bioinfo/local/build/numpy_python3/lib/python3.1/site-packages/") # numpy
sys.path.append("/bioinfo/pipelines/navicom/dev/html/lib/") # navicell
import cgi
import os
import subprocess
import cgitb
cgitb.enable()

def error(error_text):
    print("Status: 500 Internal Server Error")
    print("Content-type: text/html;charset=utf-8\n\n")
    print("<span style='color: red;'>Error: </span>" + error_text + "")
    raise ValueError(error_text)

def print_headers():
    print("Content-type: text/plain;charset=utf-8\n\n")

def log(log_entry):
    with open("/bioinfo/pipelines/navicom/dev/html/navicom_log", "a") as ff:
        ff.write(time.strftime("%H:%M %d/%m/%Y", time.localtime()) + " ")
        ff.write(str(log_entry) + "\r\n")

form = cgi.FieldStorage()
print_headers()

if ("study_selection" in form):
    study_id = form['study_selection']
else:
    error("'study_selection' field is not specified\n")

if ('url' in form):
    url = form["url"].value
else:
    error("'url' field is not specified\n")

study = os.popen("ls scratch/navicom/ | grep 'id=" + study_id + "\.txt'").readlines()
if (len(study) >= 1):
    study = study[0].strip()
else:
    subprocess.Popen(["./getData.R", study_id, "id="+study_id], stdout=subprocess.PIPE, stderr=subprocess.PIPE).communicate()
    study = os.popen("ls scratch/navicom/ | grep 'id=" + study_id + "\.txt'").readlines()[0].strip()

log("Hello data")

print("FNAME: " + study)

