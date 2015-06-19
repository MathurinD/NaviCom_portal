#/usr/bin/python3
#-*- coding:utf8 -*-

import cgi
import cgitb
cgitb.enable() # Debug for development
from navicom import *

# Redirect to the page
print("HTTP/1.1 302 Found")
print("Location: bridge.html?log_msg=NaviCell%20session%20initialized\r\n")
print("Connection: close\r\n")
# Headers
#print("<!DOCTYPE html>")
#print("<html>\n<head>\n")

form = cgi.FieldStorage()

displayMethod = form["display_selection"]
mm = [bool(re.search("isplay", NaviCom.__dict__.keys()[ii])) for ii in range(len(NaviCom.__dict__.keys()))]
valid_displays = np.array(NaviCom.__dict__.keys())[np.array(mm)]
assert displayMethod in valid_displays, "This method of display does not exist"

session_id = form["id"]
url = form["url"]
data_name = form["name"] # TODO

nc = NaviCom(name=data_name)
nc._attachSession(url, session_id)

