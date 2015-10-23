<!DOCTYPE html>
<html>
    <head>
        <title>NaviCom</title>
        <link type="text/css" rel="stylesheet" href="./bridge.css"/>
        <link rel="icon" href="favicon.ico" title="favicon" />
        <meta charset="utf-8">
    </head>
    <body>

        <?php
            include('header.html');
        ?>

	<section>
	<p>
		This documentation has a <a href="tutorial.pdf">pdf version</a>.
	</p>
	<section>

        <section id="intro">
            <h2>Introduction</h2>
            
            <p>
                NaviCom is a web portal to display data from <a href="http://www.cbioportal.org/">cBioPortal</a> on interactive maps provided by <a href="https://navicell.curie.fr/index.html">NaviCell</a>.<br/>
                NaviCom uses the R package cBioFetchR and the python package navicom, both developped by the <a href='http://bioinfo-out.curie.fr/sysbio'>Computionnal Systems Biology of Cancer group</a>, to respectively retrieve data from cBioPortal and display those data on NaviCell maps.
            </p>

            <center>
                <img src="images/NaviCom_Figure.png" alt="Organisation of the NaviCom service" width='60%'/>
            </center>
        </section>

        <section id="help">
            <h2>Tutorial</h2>
            <h3 id="help_study_selection">Data selection</h3>

            <p>
                Select a study from cBioPortal. The list of studies is optained through the cBioPortal API, and thus contains all studies available from cBioPortal at the moment.<br/>
                <p class="img"><img src="images/study_selection.png" alt="Data selection screenshot"/></p>

                NOTE: TCGA provisional studies have not been published yet, and can be subjected to <a href="http://cancergenome.nih.gov/publications/publicationguidelines">restriction concerning their use in publication</a>.
            </p>

            <h3 id="help_map_selection">NaviCell map selection</h3>
            <p>
                Select a map from the set of curated maps of the <a href="http://acsn.curie.fr">Atlas of Cancer Signaling Network collection</a>, from the <a href="https://navicell.curie.fr/pages/maps.html">NaviCell collection</a>, or provide an URL to another NaviCell map.<br/>
                <p class="img"><img src="images/map_selection.png" alt="Map selection screenshot"/></p><br/>
                The NaviCell collection map will only be used if no alternative URL is provided, and the ACSN collection map will only be used if no alternative URL is provided an no NaviCell collection map is selected.
            </p>

            <h3 id="help_display_mode">Display modes</h3>
            <p>
                Select the way the data will be displayed on the NaviCell map. Each display mode has been designed to answer a specific biological question using specific data.<br/>
                <p class="img"><img src="images/display_mode.png" alt="Display mode selection screenshot"></p>
                <ul>
                    <li><strong>Focusing on methylation and transcription</strong> provides an overview of the transcriptionnal state of the sample. It displays transcriptomic data as map staining, to see the transcription levels of each gene, and methylation data as glyph size, to assess the transcriptionnal activity of the gene.</li>
                    <li><strong>Focusing on mutations and copy number alteration</strong> provides an overview on genetic abberations of the sample. It displays copy number variation as map staining, to see the number of copies of each gene, and the mutations frequency as glyph size, to get an idea of the degree of alteration of each gene.</li>
                    <li>The <strong>complete display</strong> representation aims at displaying as much of the data as possible on the map. It allows the evalutation of the coherence of the various type of data, and to see any striking signal in any type of data. It displays mRNA, miRNA and proteomic data, as well as mutation frequency as glyph size, copy number alteration as map staining and methylation data as barplots.</li>
                    <li>The <strong>complete export</strong> option is not directly a display function, but rather exports the entire cBioPortal dataset on the NaviCell map to allow the user to visualize it in a personnalized way.</li>
                </ul>
            </p>

            <h3 id="help_color_selection">Display configuration</h3>
            <p>
                You can choose the color gradient that will be used for map staining and heatmaps. You can either use the color selector or directly type a color hexadecimal code.
                <p class="img"><img src="images/display_configuration.png" alt="The display configuration selectors"/></p>
            </p>

            <h3 id="help_loading">Processing</h3>

            <p>
                After validation of the visualisation, a loading symbol will appear on the NaviCom page, which will terminate once the cBioPortal data have been downloaded by the navicom server. 
                <p class="img"><img src="images/navicom_loading.png"/></p>
            </p>

            <h4 id="help_errors">Errors</h4>
            <p>
                If you get a <em>'Gateway TimeOut'</em> error, wait a few minutes and try again, chances are the data you asked for were not cached yet, and were too big to be downloaded in less than 5 minutes.<br/>
                If you get an <em>'Invalid map'</em> error, it means your map is not correctly annotated, and NaviCom cannot recover the HUGO names. You need to update the map accordingly.<br/>
                If you get a <em>'Not a NaviCell map'</em> error, it means the URL is not correct and does not point to a NaviCell map, or the map is broken and you need to fix it.
            </p>
        </section>

	<?php
		include('footer.html');
	?>

    </body>
</html>
