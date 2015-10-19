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

function log(text, append) {
    var logs = document.getElementById("logs");
    if (append) {
        logs.innerHTML = logs.innerHTML + "<br/>" + text;
    } else {
        logs.innerHTML = text;
    }
    console.log(text);
}

var NAVICOM = "http://navicom-dev.curie.fr/"; // TODO remove dev when getting to prod version
function exec_navicom() {
    // Start the NaviCell map and trigger NaviCom on the server

    // Control that mandatory inputs are present
    var error = "";
    var study = document.getElementById("study_selection").value;
    console.log(study);
    if (study == "empty") {
        error += "You have to select a study to be displayed<br/>";
    }
    if (error != "") {
        $("#logs").html(error);
        return;
    } else {
        $("#logs").html("");
    }

    // Start the NaviCell map
    var map_sel = document.getElementById("map_selection");
    var map = map_sel.options[map_sel.selectedIndex].value;
    var map_bis = document.getElementById("map_url").value;

    if (map_bis == "") {
        var url = "https://acsn.curie.fr/navicell/maps/" + map + "/master/index.php";
    } else {
        var url = map_bis;
    }
    // TODO Control that the url is valid
    var session_id = "navicom" + String(Math.ceil(Math.random() * 1000000000));
    $("#url").attr("value", url);
    $("#id").attr("value", session_id);

    getData(url, session_id);
    ncwin = window.open(url + "?id=@" + session_id);
    $('#loading_spinner').show();
    //ncwin.onload = displayData;
    setTimeout(displayData, '3000');
}

// First get the data, then send another request to analyse them in NaviCell
function getData(url, session_id) {
    var form = document.getElementById("nc_config");
    $('#loading_spinner').show();
    $.ajax ("./cgi-bin/getData.py", {
        async: false,
        cache: false,
        type: 'POST',
        data: $(form).serialize(),
        success: function(file) {
            $('#loading_spinner').hide();
            log("Data downloaded on the server: " + file);
        },
        error: function(e, e2, error) {
            $('#loading_spinner').hide();
            log("Error in data loading: " + error);
            log(e.responseText, true);
        }
    })
}

function displayData() {
    var form = document.getElementById("nc_config");
    $("#perform").attr("value", "display");
    $('#loading_spinner').show();
    log('Displaying data...')
    $.ajax ("./cgi-bin/displayData.py", {
        async: true,
        cache: false,
        type: 'POST',
        data: $(form).serialize(),
        success: function(file) {
            $('#loading_spinner').hide();
            log("Data displayed: " + file);
        },
        error: function(e, e2, error) {
            $('#loading_spinner').hide();
            log("Error in data display: " + error);
            log(e.responseText, true);
        }
    })
}

function getFileName(rep) {
    rep = rep.split("\n");
    var ii = 0;
    while (ii < rep.length) {
        if (rep[ii].search(/^FNAME/) != -1) {
            break;
        }
        ii += 1;
    }
    if (ii < rep.length) {
        rep = rep[ii].replace(/^FNAME: /, "").trim();
    } else {
        rep = "";
    }
    return(rep.replace(/^\//, ""));
}

function download_data() {
    // Control that mandatory inputs are present
    var error = "";
    var study = document.getElementById("study_selection").value;
    console.log(study);
    if (study == "empty") {
        error += "You have to select a study to download<br/>";
    }
    if (error != "") {
        $("#logs").html(error);
        return;
    } else {
        $("#logs").html("");
    }

    $('#loading_spinner').show();
    form = document.getElementById("nc_config");
    $("#perform").attr("value", "download");
    //log($(form).serialize());
    log("Building data file")
    //$.ajax($(form).attr('./cgi-bin/getData.py'), {
    $.ajax($(form).attr('action'), {
        async: true,
        cache: false,
        type: 'POST',
        data: $(form).serialize(),
        success: function(file){
            $('#loading_spinner').hide();
            //log("Download finished, data available at <a href=" + file + ">" + file + "</a>");
            //log(file);
            window.open(file);
        },
        error: function(e, e2, error) {
            $('#loading_spinner').hide();
            log("Error: " + error);
        }});
    //$(form).submit(); // DEBUG
}

