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
from helper_cgi import *

log("Starting the display")
form = cgi.FieldStorage()

study_id = getFormValue(form, "study_id")

if ('url' in form):
    url = form["url"].value
    url_dir = processURL(url)
    rel_dir = ".." + url_dir # Relative path for the cgis
else:
    error("'url' field is not specified\n")

fname = os.popen("ls " + rel_dir + " | grep 'id=" + study_id + "\.txt'").readlines()[0].strip()
patient = ""
if ('patient' in form and form['patient'].value!=""):
	patient = form['patient'].value
    study = os.popen("ls " + rel_dir + " | grep 'id=" + study_id + "_" + patient + "\.txt'").readlines()[0].strip()

print_headers()
#log("Loading NaviCom")

displayMethod = form["display_selection"].value
#mm = [bool(re.search("[dD]isplay", list(NaviCom.__dict__.keys())[ii] )) for ii in range(len(NaviCom.__dict__.keys()))]
#valid_displays = list(np.array(NaviCom.__dict__.keys())[np.array(mm)]) + ["completeExport"]
#if (not displayMethod in valid_displays):
    #return_error("This method of display does not exist")

if ('id' in form):
    session_id = form["id"].value
else:
    return_error("'id' field is not specified")

if ("processing" in form):
    processing = form["processing"].value
else:
    processing = "raw"

hc = getFormValue(form, "high_color")
lc = getFormValue(form, "low_color")
zc = getFormValue(form, "zero_color")
nc = NaviCom(display_config=DisplayConfig(color_gradient=[lc, hc], zero_color=zc, step_count=3))
attachNaviCell(nc, url, session_id)
nc._nv.noticeMessage('', 'Loading', 'NaviCom is performing display. It can take up to 10 minutes for big datasets<br/>This window will close automatically once the display is complete', position='middle')
nc._nv.flush()

try:
    nc.loadData(rel_dir + fname)
    log("Data loaded in NaviCom")
except:
    error("Could not load data from " + rel_dir + fname + " in navicom")
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
elif (displayMethod == "mRNAandProt"):
    nc.displayExpressionWithProteomics(processing=processing)
elif (displayMethod == "mRNAandmiRNA"):
    nc.displayExpressionWithmiRNA(processing=processing)
elif (displayMethod == "mRNAandMeth"):
    nc.displayExpressionWithMethylation(processing=processing)
elif (displayMethod == "mRNAandCNA"):
    nc.displayExpressionWithCopyNumber(processing=processing)
elif (displayMethod == "mRNAandMut"):
    nc.displayExpressionWithMutations(processing=processing)
elif (displayMethod == "mutAndGenes"):
    nc.displayMutationsWithGenomics(processing=processing)
elif (displayMethod == "mRNA"):
    nc.displayExpression(processing=processing)
else:
    error("This method of display is not valid")
nc._nv.noticeClose('')
nc._nv.flush()
log('Done')
print("FNAME: " + url_dir + fname)

