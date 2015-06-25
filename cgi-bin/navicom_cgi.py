#!/usr/bin/python3
#-*- coding:utf8 -*-

import os, sys
sys.path.append("/home/ubuntu/bin/binPython") # Access to the downloaded curie.navicell
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
    with open("navicom_log", "a") as ff:
        ff.write(time.strftime("%H:%M %d/%m/%Y", time.localtime()) + " ")
        ff.write(str(log_entry) + "\r\n")
    
def error(error_text):
    print("Status: 502 Bad Gateway")
    print("Content-type: text/plain;charset=utf-8\n\n")
    print("Error: " + error_text)
    raise ValueError(error_text)

form = cgi.FieldStorage()
log(form)

# Die if the fields set by the javascript are not present
# Other solution would be to use Location: error page
if ("study_selection" in form):
    try:
        study_id = form["study_selection"].value
        # Use an id because os.popen does not finish the R script (receives a return signal before the end)
        # TODO make a cronjob to regularly delete those unique files (else every dataset will be replicated 100000 times)
        rand_id = str(int(random.randint(0, 100000) + time.time()) % 100000)
        os.popen("Rscript ./getData.R " + study_id + " " + rand_id).readlines() # TODO maybe, use the gmt file from the map
        fname = os.popen("ls *" + rand_id + "*").readlines()[0]
    except:
        error("An error occured while trying to download the study, please check that the study ID is valid")
else:
    error("'study_selection' field is not specified\n")

if ("perform" in form):
    perform = form["perform"].value
else:
    error("'perform' field is not specified\n")

# Headers
plain_header = "Content-type: text/plain;charset=utf-8\n\n"

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
    assert displayMethod in valid_displays, "This method of display does not exist"

    session_id = form["id"].value
    url = form["url"].value
    nc = NaviCom()
    nc._attachSession(url, session_id)
    nc.loadData(fname)
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
    print(fname)
else:
    error("Invalide perform: " + perform)

