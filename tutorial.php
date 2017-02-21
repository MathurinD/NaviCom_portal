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
        This documentation also exists as a <a href="tutorial.pdf">pdf version</a> or a <a href="NaviCom_tutorial.docx">docx version</a>.
    </p>
    </section>

        <section id="intro">
            <h2>Introduction</h2>

            <center>
                <img src="images/NaviCom_Figure.png" alt="Organisation of the NaviCom service" width='60%'/>
            </center>
            <p>
                NaviCom connects between  <a href="http://www.cbioportal.org/">cBioPortal</a> and <a href="https://navicell.curie.fr/index.html">NaviCell</a> and allows to visualize various high-throughput data types simultaneously on a network maps in one click.<br/>
                NaviCom is empowered by the <a href="https://github.com/sysbio-curie/cBioFetchR">cBioFetchR</a> R package to retrieve data from cBioPortal and the python module <a href="https://github.com/sysbio-curie/navicom">navicom</a> to display those data on NaviCell signalling network maps. The interactive maps with displayed data can be browsed and analyzed in <a href="https://navicell.curie.fr/">NaviCell</a> environment.<br/>
                <br/>
                The studies available in the cBioPortal database contain large-scale cancer data sets including expression data for mRNA, microRNA, proteins expression; mutation, gene copy number and methylation profiles and beyind.<br/>
                <br/>
                Depending on the nature of data, different types of visualisation modes can be required to achieve the informative picture. NaviCom provides a default visualisation setting for simultaneous integration of the data into the big comprehensive maps of molecular interactions to visualize a complex molecular portrait. Once chosen, these settings are applied automatically, significantly reducing the time required to perform the visualisation comparing to manual mode. It also allows launching the visualisation of several datasets on different maps in parallel.<br/>
                <br/>
                The resulting maps with visualized data on top of them are interactive, they can be browsed using NaviCell Google-based navigation features that allow to visualize the data at different zoom levels, starting from the top level view, were patterns of integrated data can be grasped, up to the most detailed view at the level of individual molecules. In addition, since the whole dataset is already imported to NaviCell in a form of data tables, the user also may apply different types of analyses provided by NaviCell environment.<br/>
            </p>

        </section>

        <section id="help">
            <h2>Instructions for data integration and visualisation</h2>
            <h3>NaviCom web interface</h3>

            <center>
                <img src="images/tutorial/Home_page.png" alt="NaviCom homepage" width='60%'/>
            </center>
            <p>
                NaviCom platform is accessible via the home page that provides interactive selection panel to define study dataset, map and data display mode, to execute the visualisation and full downloadable documentation on the tool.
            </p>

            <h3 id="help_study_selection">Data selection</h3>

            <p>
                The list of studies is obtained through the cBioPortal API, and thus contains all studies available from cBioPortal at the moment. Once the study is selected, a short summary including types of data and number of samples available, is provided.<br/>
                <!--<p class="img"><img src="images/study_selection.png" alt="Data selection screenshot"/></p>-->
                <p class="img"><img src="images/tutorial/Data_Selection.png" alt="Data selection screenshot"/></p>

                <span class="note">TCGA provisional studies have not been published yet, and can be subjected to <a href="http://cancergenome.nih.gov/publications/publicationguidelines">restriction concerning their use in publication</a>.</span>
            </p>

            <h3 id="help_map_selection">NaviCell map selection</h3>
            <p>
                Select a map from the set of curated maps of the <a href="http://acsn.curie.fr">Atlas of Cancer Signaling Network collection</a>, from the <a href="https://navicell.curie.fr/pages/maps.html">NaviCell collection</a>, or provide an URL to another NaviCell map. The user can import and visualize data via NaviCom on any type of signaling networks.<br/>
                <p class="img"><img src="images/tutorial/Map_Selection.png" alt="Map selection screenshot"/></p><br/>
                <!--<p class="img"><img src="images/map_selection.png" alt="Map selection screenshot"/></p><br/>-->
                <table class="center">
                    <tr>
                        <td>
                            ACSN maps collection
                        </td>
                        <td>
                            NaviCell maps collection
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <img src="images/tutorial/ACSN_collection.png" alt="ACSN collection map selection screenshot"/>
                        </td>
                        <td>
                            <img src="images/tutorial/NaviCell_collection.png" alt="NaviCell collection map selection screenshot"/>
                        </td>
                    </tr>
					<tr>
						<td colspan="2">
							<img src="images/tutorial/modules_collection.png" alt="Modules map selection screenshot"/>
						</td>
					</tr>
                </table>
                <span class="note">The NaviCell collection map will only be used if no alternative URL is provided, and the ACSN collection map will only be used if no alternative URL is provided an no NaviCell collection map is selected.</span>
            </p>

            <h3 id="help_display_mode">Display modes</h3>

            <p>
                In NaviCell data can be graphically represented in a form of barplots, heat maps, glyphs or projected as a map staining, unique display mode coloring background areas around each entity according to the value of data value associated to this entity (Bonnet et al., 2015). NaviCom uses this graphics to overlay multiple data types. The settings are standardized and data display modes are pre-defined in NaviCom, projecting high-throughput data on NaviCell maps in automatized manner. Optimized visualization mode is chosen for each data type to achieve meaningful integrated view on complex molecular profile in the context of signalling network map.
            </p>
            <center>
                <img src="images/NaviCom_DataDisplayLegend.png" alt="Legend for NaviCom displays" width='60%'/>
            </center>
            <p class="note">
                Click <img class="inline" src="images/NaviCom_Icon.png" alt="NaviCom icon"/> icon on the upper panel of NaviCom homepage to open the display setting legend
            </p>

            <p>
                <strong>Map staining:</strong><br/> 
                The principle of map staining is in using the background of the map for visualizing the values mapped to individual molecular entities or group of entities (modules). The resulting colorful background of the network map, provides a possibility to grasp differences in the patterns of data distribution between samples or between groups of samples. To define a territory in the background occupied by a single molecular entity, the whole map territory is divided accordingly to the Voronoi cells computed from the positions of the nodes in the map. The sizes of the Voronoi cells are limited in order to avoid very large cells in the regions of the map empty from proteins or genes. Each Voronoi cell is then colored with semi-transparent color corresponding to the value mapped to the biological entity located in the center of the cell.<br/>
                <strong>Heat map:</strong><br/>
                The data is displayed as color squares. Each square represents a sample in the data table, whereas the color corresponds to the individual values represented in the data table.<br/>
                <strong>Glyph:</strong><br/>
                Glyph has three characteristics: shape, color and size, each one of those characteristics can be configured according to a different feature in the data. In NaviCom, the shape and the colour are associated with the data type and the size reflect the value in the data table.
            </p>

            <p>
                <strong>NaviCom display modes:</strong><br/>
                <table>
                    <tr>
                        <td>Complete display</td>
                        <td>all data available for a given dataset</td>
                    </tr>
                    <tr>
                        <td>Mutations and genomic data</td>
                        <td>expression-map staining / copy number-heat map / mutations-glyph 1</td>
                    </tr>
                    <tr>
                        <td>Expression and copy number</td>
                        <td>expression-maps staining / copy number-heat map</td>
                    </tr>
                    <tr>
                        <td>Expression and mutations</td>
                        <td>expression-maps staining / mutations-glyph 1</td>
                    </tr>
                    <tr>
                        <td>Expression and methylation</td>
                        <td>expression-maps staining / methylation-glyph 2</td>
                    </tr>
                    <tr>
                        <td>Expression and miRNA</td>
                        <td>expression-maps staining / miRNA-glyph 3</td>
                    </tr>
                    <tr>
                        <td>Expression and proteomics</td>
                        <td>expression-maps staining / proteome-glyph 4</td>
                    </tr>
                    <tr>
                        <td>Expression</td>
                        <td>expression-map staining</td>
                    </tr>
                </table>
            </p>
            <p>
                Select data display mode according to the data available in the chosen dataset and the scientific question to answer.<br/>
            </p>
            <p class="img"><img src="images/tutorial/Display_selection.png" alt="Display mode selection screenshot"></p>
            <table class="center">
                <tr>
                    <td><strong>Display modes</strong></td>
                </tr>
                <tr>
                    <td><img src="images/tutorial/Display_modes.png" alt="Display mode selection dropdown menu screenshot"></td>
                </tr>
            </table>

            <p>
                These pre-defined settings are flexible; after the data integration into the maps, the user can continue adjusting the visualization modes according to the scientific question.
            </p>

            <p class="note">
                The NaviCom platform provides the possibility to export the dataset from cBioPortal directly to NaviCell in order to perform some custom visualisations.
            </p>

            <h3 id="help_color_selection">Display configuration</h3>
            <p>
                The color gradient for map staining and heatmaps can be adjusted. For colour adjustment, use the color selector or directly type a color hexadecimal code
                <p class="img"><img src="images/tutorial/Display_configuration.png" alt="The display configuration selectors"/></p>
            </p>

            <h3 id="help_loading">Processing</h3>

            <p>
                After validation of the visualisation, a loading symbol will appear on the NaviCom page, which will terminate once the cBioPortal data have been downloaded by the navicom server. 
                <p class="img"><img src="images/navicom_loading.png"/></p>
            </p>

            <h4 id="help_errors">Errors</h4>
            <p>
                <strong>Invalid map</strong>: update HUGO names of entities on the map<br/>
                <span class="meaning">map entities are not annotated correctly, NaviCom cannot recover the HUGO names.</span><br/> 
                <strong>Not a NaviCell map</strong>:  update the url or update the correct map<br/>
                <span class="meaning">the URL or the map is not correct.</span><br/>
                <strong>Gateway TimeOut</strong>: wait a few minutes and try again<br/>
                <span class="meaning">the requested data were not cached yet, and were too big to be downloaded in less than 5 minutes.</span>
            </p>

            <h3>Application examples</h3>

            <center>
                <img img src="images/tutorial/Adrenocortical_Carcinoma_92_samples_CellCycle_Expression_mutations.png" alt="Organisation of the NaviCom service" width='50%'/>
            </center>
            <center>
                <img img src="images/tutorial/Adrenocortical_Carcinoma_92_samples_CellCycle_Expression_mutations_zoom.png" alt="Organisation of the NaviCom service" width='30%'/>
            </center>
            <center>
                Adrenocortical Carcinoma "Expression and mutations"<br/>
                <em>expression-map staining / mutations-blue triangle</em>
            </center>

            <p>
            </p>

            <center>
                <img img src="images/tutorial/Adrenocortical_Carcinoma_92_samples_CellCycle_triple.png" alt="Organisation of the NaviCom service" width='50%'/>
            </center>
            <center>
                Adrenocortical Carcinoma "Mutations and genomic data"<br/>
                <em>expression-map staining / copy number-heat map/ mutations-blue triangle</em>
            </center>

        </section>

    <?php
        include('footer.html');
    ?>

    </body>
</html>
