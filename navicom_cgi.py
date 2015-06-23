#!/usr/bin/python3
#-*- coding:utf8 -*-

import os, sys
sys.path.append("/home/ubuntu/bin/binPython") # Access to the downloaded curie.navicell
import time
import cgi
import cgitb
cgitb.enable() # Debug for development

from navicom import *

# Redirect to the page
if __name__ == '__main__':
    #print("HTTP/1.1 302 Found")
    #print("")
    #print("Location: bridge.html?log_msg=NaviCell%20session%20initialized,%20data%20are%20loading\r\n")
    #print("Connection: close\r\n")
    print("Content-type: text/html")
    print("Location: ./bridge.php?log_msg=NaviCell%20session%20initialized,%20data%20are%20loading\r\n")

form = cgi.FieldStorage()
with open("navicom_log", "w") as ff:
    ff.write(time.strftime("%H:%M %d/%m/%Y", time.localtime()) + " ")
    ff.write(str(form) + "\r\n")

print("Content-type: text/html\r\n")
print("Sucess")

#study_id = form["study_selection"]
#dname = os.popen("Rscript getData.R " + study_id) # TODO maybe, use the gmt file from the map

#action = form["action"]
#for ll in dname.readlines():
    #if (re.match("^FNAME:", ll)):
        #fname = re.sub("^FNAME: ", ll)
        #break
#if (action == "download"):
    #print("fname")
    #print("Connection: close\r\n")
#elif (action == "display"):
    #displayMethod = form["display_selection"]
    #mm = [bool(re.search("isplay", NaviCom.__dict__.keys()[ii])) for ii in range(len(NaviCom.__dict__.keys()))]
    #valid_displays = list(np.array(NaviCom.__dict__.keys())[np.array(mm)])
    #assert displayMethod in valid_displays, "This method of display does not exist"

    #session_id = form["id"]
    #url = form["url"]
    #nc = NaviCom(name=data_name)
    #nc._attachSession(url, session_id)
    #nc.loadData(fname)

    #processing = form["processing"]
    #if (displayMethod == "completeDisplay"):
        #nc.completeDisplay(processing = processing)
    #elif (displayMethod == "displayMethylome"):
        #nc.displayMethylome(processing=processing) # background = "auto" ?
    #elif (displayMethod == "displayMutations"):
        #nc.displayMutations()

