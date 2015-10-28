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
        <link rel="icon" href="favicon.ico" title="favicon" />
        <meta charset="utf-8">
    </head>
    <body>
        <noscript>
        This page relies on javascript to perform its functions. You must allow javascript in your browser to be able to use it.
        </noscript>

        <?php
            include('header.html');
        ?>

        <section>
        <p>
            <!--NaviCom uses the display function defined in the <a href="https://github.com/MathurinD/navicom">navicom</a> python package (which is <a href="./refman.pdf">fully documented</a>).<br/>-->
        <!--Note that downloading non cached data from <a href="http://www.cbioportal.org">cBioPortal</a> take a long time (several minutes). Displaying the data to NaviCell can also be long depending on your connection, your computer and the version of your browser.<br/>-->

        Welcome to NaviCom, a platform for generating interactive network based molecular portraits using high-throughput datasets.<br/>
        NaviCom connects between <a href="http://www.cbioportal.org">cBioPortal</a> database and <a href="http://navicell.curie.fr">NaviCell web service</a> and allows to display various high-throughput data types simultaneously on the network maps in one click.<br/>
        <br/>
        Select a study to fetch data, the network map to display the data on, and the type of display. Click ‘Perform data visualization’ to obtain a network-based molecular portrait.<br/>
For more details, see the <a href="./tutorial.php">tutorial</a>.<br/>
        <br/>
        <span class='small'>Note that displaying data on network maps may take several minutes depending on the size of the dataset and the map.<br/>
        Supported browsers&nbsp;: Firefox, Google Chrome, Safari.</span>
        </p>

        <form id="nc_config" target="_blank" method="post">
            <fieldset>
            <legend for="study_selection">Data
                <a href="./tutorial.php#help_study_selection"><img class="select_help" alt="Question mark" title="cBioPortal study" src="./images/question-mark.png"></a>
            </legend><br/><br/>
            <select id="study_selection" name="study_selection" onchange="cbiolink();">
                <?php
                $studies = array();
                exec("./cgi-bin/listStudies.R", $studies, $return);

                if ($return != 0) {
                    echo('<option value="laml_tcga_pub">Acute Myeloid Leukemia</option>');
                    echo('<option value="acc_tcga">Adenoid Cystic Carcinoma</option>');
                } else {
                    $line = preg_split("/ +/", $studies[1]);
                    $name = $line[2];
                    for ($jj=3; $jj < count($line); $jj++) {
                        $name .= " " . $line[$jj];
                    }
                    echo("<option value='{$line[1]}' selected>{$name}</option>");

                    for ($ii=2; $ii <count($studies)-1; $ii++) {
                        $line = preg_split("/ +/", $studies[$ii]);
                        $name = $line[2];
                        for ($jj=3; $jj < count($line); $jj++) {
                            $name .= " " . $line[$jj];
                        }
                        echo("<option value='{$line[1]}'>{$name}</option>");
                        }
                    }
                ?>
            </select>
            <?php
            if ($return != 0) {
                echo("<p>An error occured while listing the studies (RETURN STATUS: $return)</p>");
            }
            ?>
            <!--or <input type="file" id="study_file">-->
            <p id="cbiolink"></p>
            </fieldset>

            <fieldset>
            <legend for="map_selection">Map
                <a href="./tutorial.php#help_map_selection"><img class="select_help" alt="Question mark" title="Map to use to display the data" src="./images/question-mark.png"></a></legend><br/>
            ACSN collection:
            <select id="map_selection" name="map_selection">
                <option value="acsn" title="The global map of ACSN">ACSN global map</option>
                <option value="apoptosis" title="Apoptosis and mitochondria metabolism map">Apoptosis map</option>ewing
                <option value="survival" title="Cell survival map">Cell survival map</option>
                <option value="emtcellmotility" title="Epithelial-to-mesenchymal transition and cell mobitility map">EMT and cell mobility map</option>
                <option value="cellcycle" title="Cell cycle map" selected>Cell cycle map</option>
                <option value="dnarepair" title="DNA repair map">DNA repair map</option>
            </select>
            <br/>
            or
            <br/>
            NaviCell collection:
            <select id="map_navicell" name="map_navicell">
                <option value="" title=""></option>
                <option value="ewing" title="Ewing Sarcoma">Ewing Sarcoma signaling network</option>
                <option value="signallingnetworkofemtregulation" title="Crosstalk between Notch and p53 signaling pathways">Crosstalk between Notch and p53 signaling pathways</option>
                <option value="alzheimer" title="Signaling pathways of Alzheimer's disease">Signaling pathways of Alzheimer's disease</option>
                <option value="mastcellactivation" title="Mast Cell Activation Network">Mast Cell Activation Network</option>
            </select>
            <br/>
            or
            <br/>
            <input type="text" title="URL of a NaviCell map" id="map_url" placeholder="Alternative map URL (ex: https://navicell.curie.fr/navicell/maps/ewing/master/)"/>
            </fieldset>
            <!-- TODO input fields to specify local data or another map -->

            <fieldset>
            <legend for="display_selection">Display modes
                <a href="tutorial.php#help_display_mode"><img class="select_help" alt="Question mark" title="Method from navicom to use to display data" src="./images/question-mark.png"></a>
            </legend><br/>
            <select id="display_selection" name="display_selection">
                <!--<option value="displayOmics" title="Display on omics data available in the dataset">Omics display</option>-->
                <option value="completeExport" title="Export all data available for the dataset to  NaviCell"></option>
                <option value="completeDisplay" title="A dense display with as many data as possible displayed on the map" selected>Complete display</option>
                <option value="displayMethylome" title="Display methylation data on top of RNA data">Focus on methylation and gene expression</option>
                <option value="displayMutations" title="Display mutations data as glyph on top of CNA data">Focus on mutations and copy number alteration</option>
                <option value="mRNAandProt" title="Display proteomics data as glyphs and gene expression as map staining">Display proteomics and gene expression</option>
                <option value="mRNAandmiRNA" title="Display miRNA data as glyphs and gene expression as map staining">Display miRNA and gene expression</option>
                <option value="mRNAandMeth" title="Display methylation data as glyphs and gene expression as map staining">Display methylation and gene expression</option>
            </select>
            <br/>
            or
            <br/>
            <button id="completeExport" type="button">Export the dataset to NaviCell</button> and perform the visualisation manually.
            </fieldset>

            <!--<fieldset id="samples_selection">-->
                <!--<legend></legend>-->
                <!--Choose groups to display-->
            <!--</fieldset>-->

            <fieldset>
                <legend>Display configuration
                <a href="./tutorial.php#help_color_selection"><img class="select_help" alt="Question mark" title="Colors to use for the map staining and heatmaps" src="./images/question-mark.png"></a>
                </legend><br/><br/>
                <label for="high_color" class="colsel">Color for highest values:</label>
                <input class="color" id="high_color" value="FF0000" name="high_color"/>
                <br/>
                <label for="zero_color" class="colsel">Color for zero (if present):</label>
                <input class="color" id="zero_color" name="zero_color" value"FFFFFF">
                <br/>
                <label for="low_color" class="colsel">Color for lowest values:</label>
                <input class="color" id="low_color" value="00FF00" name="low_color"/>
            </fieldset>

            <section id="logs">
                <?php
                    if (isset($_GET["log_msg"])) {
                        echo $_GET["log_msg"];
                    }
                ?>
            </section>

            <section id="action_buttons">
                <p id="loading">
                    <img src="./images/ajax-loader.gif" id="loading_spinner"/>
                </p>

                <button id="nc_perform" type="button">Perform data visualisation</button>
                <br/>
                <a href="tutorial.php#help_loading"><img class="select_help" alt="Question mark" title="Retrieve cBioPortal data and display them on the selected NaviCell map" src="./images/question-mark.png"></a>
                <br/>
                <br/>
                (or <button id="data_download" type="button">Download cBioPortal data</button>)
                <br/>
                <input type='hidden' value='none' name='perform' id='perform'>
                <input type="hidden" value="" id="url" name="url">
                <input type="hidden" value=0 id="id" name="id">
            </section>
        </form>

        </section>

        <?php
            include('footer.html');
        ?>

    </body>
</html>
