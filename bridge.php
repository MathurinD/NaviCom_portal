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
                $studies = file("/scratch/navicom/all_studies.txt");
                for ($ii=0; $ii < count($studies); $ii++) {
                    $line = preg_split("/ /", $studies[$ii]);
                    $nsamples = $line[2];
                    $id = preg_replace("/.*id=(.*).txt$/", "\\1", $line[0]);
                    $name = str_replace("_", " ", $line[0]);
                    $name = preg_replace("/id=.*.txt$/", "", $name);
                    $methods = join(", ", array_slice($line, 3, count($line)) );
                    $ii++;
                    $patients = $studies[$ii];
                    if (preg_match("/Adrenocortical Carcinoma/", $name)) {
                    echo ("<option value='${methods}|{$id}|{$nsamples}|{$patients}' selected>${name}</option>");
                    } else {
                        echo ("<option value='${methods}|{$id}|{$nsamples}|{$patients}'>${name}</option>");
                    }
                }
                ?>
            </select>
            <!--or <input type="file" id="study_file">-->
            <p id="cbiolink"></p>
            Patients:
            <select id="patient" name="patient">
                <option value="" name=""></option>
            </select>
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
                <optgroup label="---- Modules ----">
                <optgroup label="apoptosis">
                    <option value="AKT_MTOR">AKT_MTOR</option>
                    <option value="APOPTOSIS_GENES">APOPTOSIS_GENES</option>
                    <option value="CASPASES">CASPASES</option>
                    <option value="HIF1">HIF1</option>
                    <option value="MITOCH_METABOLISM">MITOCH_METABOLISM</option>
                    <option value="MOMP_REGULATION">MOMP_REGULATION</option>
                    <option value="TNF_RESPONSE">TNF_RESPONSE</option>
                </optgroup>
                <optgroup label="survival">
                    <option value="HEDGEHOG">HEDGEHOG</option>
                    <option value="MAPK">MAPK</option>
                    <option value="PI3K_AKT_MTOR">PI3K_AKT_MTOR</option>
                    <option value="WNT_CANONICAL">WNT_CANONICAL</option>
                    <option value="WNT_NON_CANONICAL">WNT_NON_CANONICAL</option>
                </optgroup>
                <optgroup label="emtcellmotility">
                    <option value="CELL_CELL_ADHESIONS">CELL_CELL_ADHESIONS</option>
                    <option value="ECM">ECM (Extra Cellular Matrix)</option>
                    <option value="CYTOSKELETON_POLARITY">CYTOSKELETON_POLARITY</option>
                    <option value="EMT_REGULATORS">EMT_REGULATORS</option>
                    <option value="CELL_MATRIX_ADHESIONS">CELL_MATRIX_ADHESIONS</option>
                    <option value="CELL_CELL_ADHESIONS">CELL_CELL_ADHESIONS</option>
                </optgroup>
                <optgroup label="cellcycle">
                    <option value="APC">APC</option>
                    <option value="APOPTOSIS_ENTRY">APOPTOSIS_ENTRY</option>
                    <option value="CDC25">CDC25</option>
                    <option value="CYCLINA">CYCLINA</option>
                    <option value="CYCLINB">CYCLINB</option>
                    <option value="CYCLINC">CYCLINC</option>
                    <option value="CYCLIND">CYCLIND</option>
                    <option value="CYCLINE">CYCLINE</option>
                    <option value="CYCLINH">CYCLINH</option>
                    <option value="E2F1">E2F1</option>
                    <option value="E2F4">E2F4</option>
                    <option value="E2F6">E2F6</option>
                    <option value="INK4">INK4</option>
                    <option value="P21CIP">P21CIP</option>
                    <option value="P27KIP">P27KIP</option>
                    <option value="RB">RB</option>
                    <option value="WEE">WEE</option>
                </optgroup>
                <optgroup label="dnarepair">
                    <option value="BER">BER (Base excision repair)</option>
                    <option value="DR">DR</option>
                    <option value="FANCONI">FANCONI</option>
                    <option value="G1_CC_PHASE">G1_CC_PHASE</option>
                    <option value="G1_S_CHECKPOINT">G1_S_CHECKPOINT</option>
                    <option value="G2_CC_PHASE">G2_CC_PHASE</option>
                    <option value="G2_M_CHECKPOINT">G2_M_CHECKPOINT</option>
                    <option value="HR">HR</option>
                    <option value="M_CC_PHASE">M_CC_PHASE</option>
                    <option value="MMEJ">MMEJ</option>
                    <option value="MMR">MMR</option>
                    <option value="NER">NER (Nucleotide excision repair)</option>
                    <option value="NHEJ">NHEJ</option>
                    <option value="S_CC_PHASE">S_CC_PHASE</option>
                    <option value="S_PHASE_CHECKPOINT">S_PHASE_CHECKPOINT</option>
                    <option value="SPINDLE_CHECKPOINT">SPINDLE_CHECKPOINT</option>
                    <option value="SSA">SSA (Single-strang annealing)</option>
                    <option value="TLS">TLS (Translesion synthesis)</option>
                </optgroup>
                </optgroup>
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

            <fieldset>
            <legend for="display_selection">Display modes
                <a href="tutorial.php#help_display_mode"><img class="select_help" alt="Question mark" title="Method from navicom to use to display data" src="./images/question-mark.png"></a>
            </legend><br/>
            <select id="display_selection" name="display_selection">
                <option value="completeExport" title="Export all data available for the dataset to  NaviCell"></option>
                <option value="completeDisplay" title="A dense display with as many data as possible displayed on the map">Complete display</option>
                <option value="mutAndGenes" title="Display mutations as glyph with gene expression as map staining and copy number as heatmap">Mutations and genomic data</option>
                <option value="mRNAandCNA" title="Display copy number as heatmap and gene expression as map staining">Expression and copy number</option>
                <option value="mRNAandMut" title="Display mutations as glyph and gene expression as map staining" selected>Expression and mutations</option>
                <option value="mRNAandMeth" title="Display methylation data as glyphs and gene expression as map staining">Expression and methylation</option>
                <option value="mRNAandmiRNA" title="Display miRNA data as glyphs and gene expression as map staining">Expression and miRNA</option>
                <option value="mRNAandProt" title="Display proteomics data as glyphs and gene expression as map staining">Expression and proteomics</option>
                <option value="mRNA" title="Display expression data as map stainnig">Expression</option>
            </select>
            <br/>
            or
            <br/>
            <button id="completeExport" type="button">Export the dataset to NaviCell</button> and perform the visualisation manually.
            </fieldset>

            <fieldset>
                <legend>Display configuration
                <a href="./tutorial.php#help_color_selection"><img class="select_help" alt="Question mark" title="Colors to use for the map staining and heatmaps" src="./images/question-mark.png"></a>
                </legend><br/><br/>
                <table id="color_selection_table">
                    <tr>
                        <td><label for="high_color" class="colsel">Color for highest values:</label></td>

                        <td><input class="color" id="high_color" value="FF0000" name="high_color"/></td>
                </tr>
                <tr>
                    <td><label for="zero_color" class="colsel">Color for zero (if present):</label></td>
                <td><input class="color" id="zero_color" name="zero_color" value"FFFFFF"></td>
                </tr>
                <tr>
                <td><label for="low_color" class="colsel">Color for lowest values:</label></td>
                <td><input class="color" id="low_color" value="00FF00" name="low_color"/></td>
                </tr>
                </table>
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
                <input type="hidden" value="" id="study_id" name="study_id">
            </section>
        </form>

        </section>

        <?php
            include('footer.html');
        ?>

    </body>
</html>
