#!/usr/bin/python3
#-*- coding:utf8 -*-

import os, sys
#sys.path.append("/home/ubuntu/bin/binPython") # Access to the downloaded curie.navicell
sys.path.append("/bioinfo/local/build/numpy_python3/lib/python3.1/site-packages/") # numpy
#sys.path.append("/bioinfo/pipelines/navicom/dev/html/lib/navicom_package_navicom/") #navicom
sys.path.append("/bioinfo/pipelines/navicom/dev/html/lib/") # navicell
import subprocess
import numpy as np
import re, time
import random
import cgi
import cgitb
cgitb.enable() # Debug for development
#sys.tracebacklimit=0

from navicom import *

# Redirect to the page
#if __name__ == '__main__':
    #print("Content-type: text/html")
    #print("Location: ./bridge.php?log_msg=NaviCell%20session%20initialized,%20data%20are%20loading\r\n")

def log(log_entry):
    with open("/bioinfo/pipelines/navicom/dev/html/navicom_log", "a") as ff:
        ff.write(time.strftime("%H:%M %d/%m/%Y", time.localtime()) + " ")
        ff.write(str(log_entry) + "\r\n")
    
def error(error_text):
    print("Status: 500 Internal Server Error")
    print("Content-type: text/html;charset=utf-8\n\n")
    print("<span style='color: red;'>Error: </span>" + error_text + "")
    raise ValueError(error_text)

def prepare_error():
    print("Status: 500 Internal Server Error")
    print("Content-type: text/html;charset=utf-8\n\n")

def print_headers():
    print("Content-type: text/plain;charset=utf-8\n\n")

form = cgi.FieldStorage()
log(form)

# Die if the fields set by the javascript are not present
# Other solution would be to use Location: error page
if ("study_selection" in form):
    output = ""
    try:
        study_id = form["study_selection"].value
        # Use an id because os.popen does not finish the R script (receives a return signal before the end)
        # TODO make a cronjob to regularly delete those unique files (else every dataset will be replicated 100000 times)
        rand_id = str(int(random.randint(0, 100000) + time.time()) % 100000)
        #os.popen("./getData.R " + study_id + " " + rand_id).readlines() # TODO maybe, use the gmt file from the map
        output += str( subprocess.Popen(["./getData.R", study_id, rand_id], stdout=subprocess.PIPE, stderr=subprocess.PIPE).communicate() )
        fname = os.popen("ls /scratch/navicom/*" + rand_id + "*").readlines()[0].strip()
    except:
        error("An error occured while trying to download the study, please check that the study ID is valid:" + "<br/>" + output)
else:
    error("'study_selection' field is not specified\n")

if ("perform" in form):
    perform = form["perform"].value
else:
    error("'perform' field is not specified\n")

print_headers()

if (perform == "download"):
    #print "Content-type: application/octet-stream; name=\"FileName\"\r\n";
    #print "Content-Disposition: attachment; filename=\"FileName\"\r\n\n";
    print(plain_header)
    print("Download finished")
    print(fname)
elif (perform == "display"):
    displayMethod = form["display_selection"].value
    mm = [bool(re.search("[dD]isplay", list(NaviCom.__dict__.keys())[ii] )) for ii in range(len(NaviCom.__dict__.keys()))]
    valid_displays = list(np.array(NaviCom.__dict__.keys())[np.array(mm)])
    if (not displayMethod in valid_displays):
        error("This method of display does not exist")

    session_id = form["id"].value
    url = form["url"].value
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

    if ("processing" in form):
        processing = form["processing"].value
    else:
        processing = "raw"
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
    print("FNAME: " + fname)
else:
    error("Invalide perform: " + perform)

