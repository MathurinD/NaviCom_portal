#!/usr/bin/python3
#-*- coding:utf8 -*-

import os, sys

sys.path.append("/bioinfo/pipelines/navicom/dev/html/lib/") # navicell
sys.path.append("/bioinfo/local/build/numpy_python3/lib/python3.1/site-packages/") # numpy
from navicom import *
import time
import subprocess

def error(log_entry):
    with open("/bioinfo/pipelines/navicom/dev/html/navicom_errors", "a") as ff:
        ff.write(time.strftime("%H:%M %d/%m/%Y", time.localtime()) + " ")
        ff.write(str(log_entry) + "\r\n")

try:
    error(sys.argv)
except:
    pass
fname = sys.argv[1]
session_id = sys.argv[2]
url = sys.argv[3]
displayMethod = sys.argv[4]
processing = sys.argv[5]

nc = NaviCom()
try:
    nc._attachSession(url, session_id)
except:
    error("Could not attach session with id " + str(session_id))
try:
    nc.loadData(fname)
except:
    error("Could not load data from " + fname + " in navicom")
nc._browser_opened = True # The browser is opened by the client

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

# Delete the file
subprocess.Popen('rm ' + fname)
