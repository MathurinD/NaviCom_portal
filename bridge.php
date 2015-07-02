<?php
define("LOG_FILE", "./navicom_log");
define("DEST_LOGFILE", "3");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>NaviCom</title>
		<link type="text/css" rel="stylesheet" href="./bridge.css"/>
		<script type="text/javascript" src="jscolor/jscolor.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="./bridge.js"></script>
		<meta charset="utf-8">
	</head>
	<body>
		<section>
		<noscript>
		This page relies on javascript to perform its functions. You must allow javascript in your browser to be able to use it.
		</noscript>

		<header>
			<div id="logos">
				<img src="./images/portal_navicom_logo.png" id="navicom_logo" align="left">
				<img src="./images/curie_logo.jpg" align="right">
			</div>
			<h1>
				NaviCom portal
			</h1>
		</header>

		<p>
			Welcome to NaviCom portal, a link between <a href="http://www.cbioportal.org">cBioPortal</a> database and <a href="http://navicell.curie.fr">NaviCell</a> web service.<br/>
			Select a study from which you want to fetch data, the map on which you want it to be displayed and the type of display you want to see.<br/>
			NaviCom uses the display function defined in the <a href="https://github.com/MathurinD/navicom">navicom</a> python package.<br/>
			Note that downloading data from <a href="http://www.cbioportal.org">cBioPortal</a> and exporting them to NaviCell can take some time, depending on the speed of your connection and your computer.<br/>
		</p>

		<form id="nc_config" target="_blank" method="post" action="./cgi-bin/navicom_cgi.py">
			<table>
				<fieldset>
				<legend for="study_selection">Study:</legend>
				<select id="study_selection" name="study_selection">
					<option value="empty" selected>&nbsp;</option>
					<?php
					$studies = array();
					exec("cgi-bin/listStudies.R", $studies, $return);

					if ($return != 0) {
						echo('<option value="laml_tcga_pub">Acute Myeloid Leukemia</option>');
						echo('<option value="acc_tcga">Adenoid Cystic Carcinoma</option>');
					} else {
						for ($ii=1; $ii <count($studies)-1; $ii++) {
							$line = preg_split("/ +/", $studies[$ii]);
							$name = "";
							for ($jj=2; $jj < count($line); $jj++) {
								$name .= " " . $line[$jj];
							}
							echo("<option value='{$line[1]}'>{$name}</option>");
							}
						}
					?>
				</select><a href="#help_study_selection"><img class="select_help" src="./images/question-mark.png"></a>
				<?php
				if ($return != 0) {
					echo("<p>An error occured while listing the studies (RETURN STATUS: $return)</p>");
				}
				?>
				<!--or <input type="file" id="study_file">-->
				</fieldset>

				<fieldset>
				<legend for="map_selection"><a href="http://acsn.curie.fr">ACSN</a> Map:</legend>
				<select id="map_selection" name="map_selection">
					<option value="acsn" title="The global map of ACSN">ACSN global map</option>
					<option value="apoptosis" title="Apoptosis and mitochondria metabolism map">Apoptosis map</option>
					<option value="survival" title="Cell survival map">Cell survival map</option>
					<option value="emtcellmobility" title="Epithelial-to-mesenchymal transition and cell mobitility map">EMT and cell mobility map</option>
					<option value="cellcycle" title="Cell cycle map" selected>Cell cycle map</option>
					<option value="dnarepair" title="DNA repair map">DNA repair map</option>
				</select>
				or <input type="text" title="URL of a NaviCell map" id="map_url" placeholder="Alternative map URL (ex: https://navicell.curie.fr/navicell/maps/ewing/master/)"/>
				<a href="#help_map_selection"><img class="select_help" src="./images/question-mark.png"></a>
				</fieldset>
				<!-- TODO input fields to specify local data or another map -->

				<fieldset>
				<legend for="display_selection">Display mode:</legend>
				<select id="display_selection" name="display_selection">
					<option value="completeDisplay" title="A dense display with as many data as possible displayed on the map" selected>Complete display</option>
					<!--<option value="displayOmics" title="Display on omics data available in the dataset">Omics display</option>-->
					<option value="completeExport" title="Export all data available for the dataset to  NaviCell">Complete export</option>
					<option value="displayMethylome" title="Display methylation data on top of RNA data">Focus on methylation and transcription</option>
					<option value="displayMutations" title="Display mutations data as glyph on top of CNA data">Focus on mutations and copy number alteration</option>
				</select><a href="#help_display_mode"><img class="select_help" src="./images/question-mark.png"></a>
				</fieldset>

				<!--<fieldset id="samples_selection">-->
					<!--<legend></legend>-->
					<!--Choose groups to display-->
				<!--</fieldset>-->

				<fieldset>
					<legend>Display configuration</legend>
					Color for lowest values: <input class="color" id="low_color" value="00FF00" name="low_color"/><br/>
					Color for highest values: <input class="color" id="high_color" value="FF0000" name="hight_color"/><br/>
					Color for zero (if present): <input class="color" id="zero_color" name="zero_color" value"FFFFFF"><br/>
				</fieldset>

				<section id="logs">
					<?php
						if (isset($_GET["log_msg"])) {
							echo $_GET["log_msg"];
						}
					?>
				</section>
				<p>
					<img src="./images/ajax-loader.gif" id="loading_spinner"/>
				</p>
				<button id="nc_perform" onclick="exec_navicom(); return false" type="button">Perform data visualisation</button>
				<button id="data_download" onclick="download_data()" type="button">Download cBioPortal data</button>
				<br/>
				<input type='hidden' value='none' name='perform' id='perform'>
				<input type="hidden" value="" id="url" name="url">
				<input type="hidden" value=0 id="id" name="id">
			</table>
		</form>

		</section>

		<div class=separator>
		</div>

		<section id="help">
			<h2>Documentation</h2>

			<h3 id="help_study_selection">Study selection</h3>
			<p>
				Select a study from cBioPortal. The list of studies is optained from cBioPortal API, and thus contain all studies available from cBioPortal.<br/>
				Note that TCGA provisional studies have not been published yet, and can be subject to restriction concerning publication using them.
			</p>

			<h3 id="help_map_selection">NaviCell map selection</h3>
			<p>
				Select a map from the set of curated maps of the <a href="http://acsn.curie.fr">Atlas of Cancer Signaling Network</a>, or provide an URL to another NaviCell map.
			</p>

			<h3 id="help_display_mode">Display mode</h3>
			<p>
				Select the way the data will be displayed on the NaviCell map. Each display mode has been designed to answer a specific biological question using specific data.<br/>
				<ul>
					<li>Focusing on methylation provides an overview of the transcriptionnal state of the sample. It displays transcriptomic data as map staining, to see the transcription levels of each gene, and methylation data as glyph size, to assess the transcriptionnal activity of the gene.</li>
					<li>Focusing on mutations provides an overview on genetic abberations of the sample. It displays copy number variation as map staining, to see the number of copies of each gene, and the mutations frequency as glyph size, to get an idea of the degree of alteration of each gene.</li>
					<li>The complete display representation aims at displaying as much of the data as possible on the map. It allows the evalutation of the coherence of the various type of data, and to see any striking signal in any type of data.</li>
					<li>The complete export option is not directly a display function, but rather exports the entire cBioPortal dataset on the NaviCell map to allow the user to visualize it in a personnalized way.</li>
				</ul>
			</p>
		</section>

		<footer>
			<p>
				<center><b>NaviCom</b> was created and is maintained by the team <a href="http://sysbio.curie.fr/" target="_blank">"Computational Systems Biology of Cancer"</a> at the <a href="http://www.curie.fr">Institut Curie</a>.<br/>
				Copyright (c) 2015</center>
			</p>
		</footer>
	</body>
</html>
