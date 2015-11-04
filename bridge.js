/*
 * bridge.js
 * By Mathurin Dorel
 * Start the NaviCell map and send data to the server for processing
 */

// helper function for cross-browser request object
function getRequest(url, success, error) {
    var req = false;
    try{
        // most browsers
        req = new XMLHttpRequest();
    } catch (e){
        // IE
        try{
            req = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(e) {
            // try an older version
            try{
                req = new ActiveXObject("Microsoft.XMLHTTP");
            } catch(e) {
                return false;
            }
        }
    }
    if (!req) return false;
    if (typeof success != 'function') success = function () {};
    if (typeof error!= 'function') error = function () {};
    req.onreadystatechange = function(){
        if(req.readyState == 4) {
            return req.status === 200 ? 
                success(req.responseText) : error(req.status);
        }
    }
    req.open("GET", url, true);
    req.send(null);
    return req;
}

function onlyUnique(value, index, self) { 
    return self.indexOf(value) === index;
}

function cbiolink() {
    var cbl = document.getElementById("cbiolink");
    var cbs = document.getElementById("study_selection");
    if (cbs.children[cbs.selectedIndex] == "empty") {
        cbl.innerHTML = "";
    } else {
        var scbs = cbs.children[cbs.selectedIndex];
        var ss = scbs.value.split("|");
        var id = ss[1];
        var nsamples = ss[2];
        var raw_methods = ss[0].split(" ");
        console.log(raw_methods);
        var methods = Array()
        for (var ii=0; ii < raw_methods.length; ii++) {
            met = raw_methods[ii].toLowerCase();
            if (met.search(/mirna/) != -1) {
            } else if (met.search(/rna/) != -1) {
                methods.push("Expression");
            } else if (met.search(/cna/) != -1 || met.search(/gistic/) != -1) {
                methods.push("Copy Number");
            } else if (met.search(/mutation/) != -1) {
                methods.push("Mutations");
            } else if (met.search("rppa") != -1 || met.search(/prot/) != -1) {
                methods.push("Proteomics");
            } else if (met.search(/methylation/) != -1) {
                methods.push("Methylation");
            }
        }
        methods = methods.filter(onlyUnique).join(", ");

        cbl.innerHTML = "<a href='http://www.cbioportal.org/index.do?cancer_study_list=" + id + "'>" + scbs.label + "</a> (" + nsamples + " samples) on cBioPortal<br/><strong>Data types available</strong>: " + methods;
    }
}

function log(text, append) {
    var logs = document.getElementById("logs");
    if (append) {
        logs.innerHTML = logs.innerHTML + "<br/>" + text;
    } else {
        logs.innerHTML = text;
    }
    console.log(text);
}

function getStudyId() {
    var id = document.getElementById("study_selection").value;
    id = id.split("|")[1].trim();
    $("#study_id").attr("value", id);
    return(id);
}

// Ensure that mandatory inputs are present
function completeFields() {
    var error = "";
    var study = getStudyId();
    console.log(study);
    if (study == "empty") {
        error += "You have to select a study to be displayed<br/>";
    }
    if (error != "") {
        $("#logs").html(error);
        return(false);
    }

    // Selection of the NaviCell map
    log("Connecting to the NaviCell session");
    var map = document.getElementById("map_selection").value;
    var map_navicell = document.getElementById("map_navicell").value;
    var map_bis = document.getElementById("map_url").value;

    var url="";
    if (map_bis != "") {
        url = map_bis;
    } else if (map_navicell != "") {
        url = "https://navicell.curie.fr/navicell/maps/" + map_navicell + "/master/index.php"
    } else {
        url = "https://acsn.curie.fr/navicell/maps/" + map + "/master/index.php";
    }
    url = url.replace(/\/$/, "/index.php");
    url = url.replace(/.html$/, ".php");

    // TODO Control that the url is valid
    var session_id = "navicom" + String(Math.ceil(Math.random() * 1000000000));
    $("#url").attr("value", url);
    $("#id").attr("value", session_id);

    return(url);
}

function navicom_error(e, e2, error) {
    $('#loading_spinner').hide();
    log("Error: " + error);
    if (error != "Gateway Time-out ") {
        log("<span style='color: red;'>Error: </span>" + e.responseText);
    }
}

var NAVICOM = "http://navicom-dev.curie.fr/"; // TODO remove dev when getting to prod version
// Start the NaviCell map and trigger NaviCom on the server
function exec_navicom() {
    var url = completeFields();
    if (!url) { return; }
    var session_id = $("#id").attr("value");

    ncwin = window.open(url + "?id=@" + session_id);
    getData(true, url, session_id);
    //setTimeout(displayData, '3000');
}

function completeExport() {
    document.getElementById("display_selection").value = "completeExport";
    exec_navicom();
    log("Exporting data");
}

function showSpinner(text) {
    $('#loading_spinner').show();
    log(text);
}

// First get the data, then send another request to analyse them in NaviCell
function getData(one_more, url, session_id) {
    var form = document.getElementById("nc_config");
    showSpinner("Building data file");
    $.ajax ("./cgi-bin/getData.py", {
        async: true,
        cache: false,
        type: 'POST',
        data: $(form).serialize(),
        success: function(file) {
            $('#loading_spinner').hide();
            file = getFileName(file);
            log("Data downloaded on the server: " + file);
            displayData();
        },
        error: function(e, e2, error) {
            if (one_more && error == "Gateway Time-out ") {
                setTimeout(function() {
                    download_data(false)
                }, 3 * 60000); // Wait 3 minutes
            } else {
                navicom_error(e, e2, error);
            }
        }
    })
}

function displayData() {
    var form = document.getElementById("nc_config");
    $("#perform").attr("value", "display");
    showSpinner('Displaying data...<br/>It will take from 30 seconds up to 10 minutes for big datasets')
    $.ajax ("./cgi-bin/displayData.py", {
        async: true,
        cache: false,
        type: 'POST',
        data: $(form).serialize(),
        success: function(file) {
            $('#loading_spinner').hide();
            file = getFileName(file);
            if (file != "") {
                log("<a href='" + file + "'>Data displayed</a>");
            }
        },
        error: navicom_error
    })
}

function getFileName(rep) {
    console.log(rep);
    rep = rep.split("\n");
    var ii = 0;
    while (ii < rep.length) {
        if (rep[ii].search(/^FNAME/) != -1) {
            return( rep[ii].replace(/^FNAME: /, "").trim().replace(/^\//, "") );
        }
        if (rep[ii].search(/Status.*Error/) != -1) {
            log( "<span style='color: red;'>Error: </span>" + rep.join("</br>") );
        }
        ii += 1;
    }
    return("");
}

function download_data(one_more) {
    if (typeof(one_more) == 'undefined') { one_more=false; }
    var url = completeFields();
    if (!url) { return; }
    var session_id = $("#id").attr("value");
    var map_bis = document.getElementById("map_url").value;
    if (map_bis != "") {
        ncwin = window.open(url + "?id=@" + session_id);
    }
    ncwin = window.open(url + "?id=@" + session_id);

    showSpinner("Building data file")
    form = document.getElementById("nc_config");
    $("#perform").attr("value", "download");
    $.ajax("./cgi-bin/getData.py", {
        async: true,
        cache: false,
        type: 'POST',
        data: $(form).serialize(),
        success: function(file){
            $('#loading_spinner').hide();
            file = getFileName(file);
            log("Download finished");
            if (typeof ncwin !== 'undefined') { ncwin.close(); }
            window.open(file);
        },
        error: function(e, e2, error) {
            if (one_more && error == "Gateway Time-out ") {
                setTimeout(function() {
                    download_data(false)
                }, 3 * 60000); // Wait 3 minutes
            } else {
                if (typeof ncwin !== 'undefined') { ncwin.close(); }
                
                navicom_error(e, e2, error);
            }
        }});
}

// Define the onclick calls
document.addEventListener("DOMContentLoaded", function(event) { 
    document.getElementById("completeExport").onclick = completeExport;
    document.getElementById("nc_perform").onclick = exec_navicom;
    document.getElementById("data_download").onclick = download_data;
    cbiolink();
});
