<?php
require_once ('vars.php');
?>
<!DOCTYPE HTML>
<html lang="en">
  <head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-2180015-4"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-2180015-4');
    </script>

    <title>Coronavirus (COVID-19) Situaci&oacute;n mundial - Actualizaci&oacute;n diaria</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/css.css">
  </head>
  <body>
    <h1>Coronavirus (COVID-19) Situaci&oacute;n mundial - Actualizaci&oacute;n diaria</h1>
    <div class="container" style="display: flex; flex-wrap: wrap; width: 100%;">
            <div class="container-left" style="display: flex; flex-direction: column; flex-basis: 100%; flex: 1;">
                <div id="world_total_num_confirmed" class="text-cases">
                    <div class="number">
                        <?php echo number_format($numConfirmedCases, 0, ',', '.'); ?>
                    </div>
                    <div class="text">
                        Casos confirmados
                    </div>
                </div>

                <div id="world_total_num_deaths" class="text-cases">
                    <div class="number">
                        <?php echo number_format($numDeathsCases, 0, ',', '.'); ?>
                    </div>
                    <div class="text">
                        Personas fallecidas (<?php echo number_format((($numDeathsCases*100) / $numConfirmedCases), 2, ',', '.'); ?>%)
                    </div>
                </div>

                <div id="world_total_num_recovered" class="text-cases">
                    <div class="number">
                        <?php echo number_format($numRecoveredCases, 0, ',', '.'); ?>
                    </div>
                    <div class="text">
                        Personas recuperadas (<?php echo number_format((($numRecoveredCases*100) / $numConfirmedCases), 2, ',', '.'); ?>%)
                    </div>
                </div>


                <div id="affected_countries" class="text-cases">
                    <div class="number">
                        <?php echo $numCountries; ?>
                    </div>
                    <div class="text">
                        Paises afectados
                    </div>
                </div>
            </div>
            <div style="display: flex; flex-direction: column; flex: 5">
                <div>
                <figure class="highcharts-figure">
                    <div id="container_confirmed"></div>
                </figure>
                </div>
                <div>
                <figure class="highcharts-figure">
                    <div id="container_deaths"></div>
                </figure>
                </div>
                <div>
                <figure class="highcharts-figure">
                    <div id="container_recovered"></div>
                </figure>
                </div>
            </div>
    </div>
    

    <script type="text/javascript" src="result.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script type="text/javascript">
        Highcharts.chart('container_confirmed', {
            chart: {
                zoomType: 'x'
            },
            
            title: {
                text: 'COVID19 - Casos confirmados'
            },

            yAxis: {
                title: {
                    text: 'Numero de casos confirmados'
                }
            },

            xAxis: {
                type: 'datetime'
            },

            legend: {
                enabled: false
            },

            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    }
                }
            },

            series: resultConfirmed
        });  


        Highcharts.chart('container_deaths', {
            chart: {
                zoomType: 'x'
            },
            
            title: {
                text: 'COVID19 - Personas fallecidas'
            },

            yAxis: {
                title: {
                    text: 'Numero de personas fallecidas'
                }
            },

            xAxis: {
                type: 'datetime'
            },

            legend: {
                enabled: false
            },

            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    }
                }
            },

            series: resultDeaths
        }); 


        Highcharts.chart('container_recovered', {
            chart: {
                zoomType: 'x'
            },
            
            title: {
                text: 'COVID19 - Personas recuperadas'
            },

            yAxis: {
                title: {
                    text: 'Numero de personas recuperdas'
                }
            },

            xAxis: {
                type: 'datetime'
            },

            legend: {
                enabled: false
            },

            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    }
                }
            },

            series: resultRecovered
        });
    </script>
    
    <footer>
      <div>Data updated at <?php echo date("d-m-Y 01:00:00"); ?>a.m.</div>
    </footer>
    <div id="datasource-advertise">The data source from the <a href="https://github.com/CSSEGISandData" title="CSSE at Johns Hopkins University Github repository" target="_blank">CSSE at Johns Hopkins University Github repository</a></div>
  </body>
</html>