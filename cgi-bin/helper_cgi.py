import re
import time
import sys

def error(error_text):
    print("Status: 500 Internal Server Error")
    print("Content-type: text/html;charset=utf-8\n\n")
    print(error_text)
    raise ValueError(error_text) # Change to print in prod

def return_error(error_text):
    print("Status: 500 Internal Server Error\n\n")
    print(error_text)
    print("<a href='tutorial.php#help_errors'>(More informations)</a>")
    sys.exit(0)

def print_dl_headers(fname="data.txt"):
    #print("Content-type: text/plain;charset=utf-8\n\n")
    print("Content-type: text/plain;charset=utf-8")
    print("Content-Disposition: attachement; filename='"+fname+"'\n")

def print_headers():
    print("Content-type: text/plain;charset=utf-8\n\n")

def log(log_entry):
    pass
#    with open("/bioinfo/pipelines/navicom/dev/html/navicom_log", "a") as ff:
#        ff.write(time.strftime("%H:%M %d/%m/%Y", time.localtime()) + " ")
#        ff.write(str(log_entry) + "\r\n")

def processURL(url):
    if ( re.search("acsn.curie.fr/navicell/maps/(cellcycle|emtcellmobility|dnarepair|survival|apoptosis|acsn)", url) ):
        url_dir = 'acsn'
    else:
        url_dir = re.sub('/index.(php|html)$', '', url)
        url_dir = re.sub('^https?://', '', url_dir)
        url_dir = re.sub('/', '_', url_dir)
        url_dir = re.sub("maps_", "", url_dir)
        url_dir = re.sub("_$", "", url_dir)
    log("URL: " + url_dir)
    url_dir = "/scratch/navicom/" + url_dir + "/"
    return(url_dir)

def getFormValue(form, key, alt=None):
    if (key in form):
        return(form[key].value)
    elif (alt != None):
        return(alt)
    else:
        print_headers()
        error("'" + key + "' field is not specified")

# Attach the NaviCell session to the NaviCom object
def attachNaviCell(nc, url, session_id):
    attached = False
    iTime = time.time()
    timeout = 5
    while ( not attached and (time.time()-iTime) < timeout ):
        try:
            nc._attachSession(url, session_id)
            attached = True
        except: 
            time.sleep(0.01)
    try:
        nc._nv._waitForReady('')
    except:
        #error("Could not attach session with id " + str(session_id))
        return_error("Not a NaviCell map (Could not attach the map)")
