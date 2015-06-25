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

    // Transfert data to the NaviCom server
    var form = document.getElementById("nc_config");
    nvSession(form, url);
    $("#perform").attr("value", "display");
    $('#loading_spinner').show();
    log("Submission");
    $.ajax($(form).attr('action'), {
        async: true,
        cache: false,
        type: 'POST',
        data: $(form).serialize(),
        success: function(file){
            $('#loading_spinner').hide();
            log("Display finished, data available at <a href=" + NAVICOM + file + ">" + file + "</a>");
        },
        error: function(e, e2, error) {
            $('#loading_spinner').hide();
            log("Error: " + error);
			log(e, true);
        }});
}

function nvSession(form, url) {
    var session_id = "@navicom" + String(Math.ceil(Math.random() * 1000000000));
    window.open(url + "?id=" + session_id);
    $("#url").attr("value", url);
    $("#id").attr("value", session_id);
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
    log($(form).serialize());
    $.ajax($(form).attr('action'), {
        async: true,
        cache: false,
        type: 'POST',
        data: $(form).serialize(),
        success: function(file){
            $('#loading_spinner').hide();
            //log("Download finished, data available at <a href=" + file + ">" + file + "</a>");
            window.open(NAVICOM + file);
        },
        error: function(e, e2, error) {
            $('#loading_spinner').hide();
            log("Error: " + error);
        }});
    $(form).submit(); // DEBUG
}

