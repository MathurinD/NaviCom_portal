import re
import time

def error(error_text):
    print("Status: 500 Internal Server Error")
    print("Content-type: text/html;charset=utf-8\n\n")
    print("<span style='color: red;'>Error: </span>" + error_text + "")
    raise ValueError(error_text)

def print_dl_headers(fname="data.txt"):
    #print("Content-type: text/plain;charset=utf-8\n\n")
    print("Content-type: text/plain;charset=utf-8")
    print("Content-Disposition: attachement; filename='"+fname+"'\n")

def print_headers():
    print("Content-type: text/plain;charset=utf-8\n\n")

def log(log_entry):
    with open("/bioinfo/pipelines/navicom/dev/html/navicom_log", "a") as ff:
        ff.write(time.strftime("%H:%M %d/%m/%Y", time.localtime()) + " ")
        ff.write(str(log_entry) + "\r\n")

def processURL(url):
    if ( re.search("acsn.curie.fr/navicell/maps/(cellcycle|emtcellmobility|dnarepair|survival|apoptosis|acsn)", url) ):
        url_dir = 'acsn'
    else:
        url_dir = re.sub('/index.(php|html)$', '', url)
        url_dir = re.sub('^https?://', '', url_dir)
        url_dir = re.sub('/', '_', url_dir)
        url_dir = re.sub("maps_", "", url_dir)
    log(url_dir)
    url_dir = "/scratch/navicom/" + url_dir + "/"
    return(url_dir)
