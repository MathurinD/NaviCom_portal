#!/usr/bin/python3
#-*- coding:utf8 -*-

import os, sys
sys.path.append("/bioinfo/local/build/numpy_python3/lib/python3.1/site-packages/") # numpy
sys.path.append("/bioinfo/pipelines/navicom/dev/html/lib/") # navicell
import numpy as np
import subprocess
import re, time
import cgi
import cgitb
cgitb.enable() # Debug for development
#sys.tracebacklimit=0

from navicom import *

def log(log_entry):
    with open("/bioinfo/pipelines/navicom/dev/html/navicom_log", "a") as ff:
        ff.write(time.strftime("%H:%M %d/%m/%Y", time.localtime()) + " ")
        ff.write(str(log_entry) + "\r\n")

def print_headers():
    print("Content-type: text/plain;charset=utf-8\n\n")
    
def error(error_text):
    print("Status: 500 Internal Server Error")
    print("Content-type: text/html;charset=utf-8\n\n")
    print("<span style='color: red;'>Error: </span>" + error_text + "")
    raise ValueError(error_text)

log("Starting the display")
form = cgi.FieldStorage()

if ("study_selection" in form):
    study_id = form["study_selection"].value
else:
    error("'study_selection' field is not provided")

if ('url' in form):
    url = form["url"].value
else:
    error("'url' field is not specified\n")

fname = os.popen("ls ../scratch/navicom/* | grep 'id=" + study_id + "\.txt'").readlines()[0].strip()

print_headers()
log("Loading NaviCom")

displayMethod = form["display_selection"].value
mm = [bool(re.search("[dD]isplay", list(NaviCom.__dict__.keys())[ii] )) for ii in range(len(NaviCom.__dict__.keys()))]
valid_displays = list(np.array(NaviCom.__dict__.keys())[np.array(mm)])
if (not displayMethod in valid_displays):
    error("This method of display does not exist")

if ('id' in form):
    session_id = form["id"].value
else:
    error("'id' field is not specified")

if ("processing" in form):
    processing = form["processing"].value
else:
    processing = "raw"

nc = NaviCom()
log("Successfully loaded NaviCom")

try:
    nc._attachSession(url, session_id)
    log("NaviCom attached to the NaviCell session")
except:
    error("Could not attach session with id " + str(session_id))

try:
    nc.loadData(fname)
    log("Data loaded in NaviCom")
except:
    error("Could not load data from " + fname + " in navicom")
nc._browser_opened = True # The browser is opened by the client

#subprocess.Popen("./navicom_display.py '" + fname + "' '" + session_id + "' '" + url + "' '" + displayMethod + "' '" + processing + "' &", shell=True)
#subprocess.Popen(["./navicom_display.py", fname, session_id, url, displayMethod, processing, "&"])
log("Running with " + fname)
if (displayMethod == "completeDisplay"):
    nc.completeDisplay(processing=processing)
elif (displayMethod == "displayMethylome"):
    nc.displayMethylome(processing=processing) # background = "auto" ?
elif (displayMethod == "displayMutations"):
    nc.displayMutations(processing=processing) # background ?
elif (displayMethod == "completeExport"):
    nc.completeExport()
else:
    error("This method of display does not exist")
log('Done')
print("FNAME: " + re.sub('^\.\./', '', fname))

