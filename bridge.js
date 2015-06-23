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

function log(text) {
	var logs = document.getElementById("logs");
	logs.innerHTML = text;
	console.log(text);
}

var NAVICOM = "http://navicom.curie.fr/";
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
	$(form).append("<input type='hidden' value='display' id='action'>");
	$('#loading_spinner').show();
	$(form).submit( function() {
		$.post(
			$(this).attr('action'),
			$(this).serialize(),
			function(file){
				$('#loading_spinner').hide();
				log("Download finished, data available at <a href=" + file + ">" + file + "</a>");
			},
			function(e) {
				log("Error: " + e);
			});
		});
}

function nvSession(form, url) {
	var session_id = "@navicom" + String(Math.ceil(Math.random() * 1000000000));
	//window.open(url + "?id=" + session_id);
	var url_post = document.createElement("input");
	url_post.setAttribute("type", "hidden");
	url_post.setAttribute("value", url);
	url_post.setAttribute("id", "url");
	form.appendChild(url_post)
	var id_post = document.createElement("input");
	id_post.setAttribute("type", "hidden");
	id_post.setAttribute("value", session_id);
	id_post.setAttribute("id", "id");
	form.appendChild(id_post)
	form.setAttribute("method", "post");
	form.setAttribute("action", "./navicom_cgi.py");
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
	$(form).append("<input type='hidden' value='dowload' id='action'>")
	$(form).submit(function(){
		$.post(
			$(this).attr('action'),
			$(this).serialize(),
			function(file){
				window.open(NAVICOM + file);
				$("#loading_spinner").hide();
			});
		});
}

