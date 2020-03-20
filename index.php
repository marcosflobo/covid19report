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

    <title>Coronavirus (COVID-19) Global Situation - daily update</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/css.css">
  </head>
  <body>
    <h1>Coronavirus (COVID-19) Global Situation - daily update</h1>
    <figure class="highcharts-figure">
        <div id="container_confirmed"></div>
    </figure>
    
    <figure class="highcharts-figure">
        <div id="container_deaths"></div>
    </figure>

    <figure class="highcharts-figure">
        <div id="container_recovered"></div>
    </figure>

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
                text: 'COVID19 - Confirmed cases'
            },

            yAxis: {
                title: {
                    text: 'Number of Confirmed Cases'
                }
            },

            xAxis: {
                type: 'datetime'
            },

            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
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
                text: 'COVID19 - Deaths cases'
            },

            yAxis: {
                title: {
                    text: 'Number of Deaths Cases'
                }
            },

            xAxis: {
                type: 'datetime'
            },

            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
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
                text: 'COVID19 - Recovered cases'
            },

            yAxis: {
                title: {
                    text: 'Number of Recovered Cases'
                }
            },

            xAxis: {
                type: 'datetime'
            },

            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
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