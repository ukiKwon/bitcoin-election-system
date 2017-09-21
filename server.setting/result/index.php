<!DOCTYPE HTML>
<!--
	Big Picture by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
        <title>Voting Result Chart</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var options = {
                chart: {
                    renderTo: 'total',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'

                },
                title: {
                    text: 'Total Voting Result'
                },
                tooltip: {
                    formatter: function() {
                        return '<b>' + this.point.name + '</b>: ' + this.percentage + ' %' + '<br>  (' + this.y + '표' + ')';;
                    }
                },
                
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            formatter: function() {
                                return '<b>' + this.point.name + '</b>: ' + this.percentage.toFixed(2) + ' %' + '<br>  (' + this.y + '표' + ')';
                            }
                        },
                        showInLegend: true
                    }
                },
                series: [{}]
            }

            $.getJSON("../total.php", function(json) {
                console.log(json);
                options.series[0]= json;        
                //options.series[0] = json;
                chart = new Highcharts.Chart(options);
            });

        });
        
        //************************************************************************************************************
        
        
        $(document).ready(function() {
            var options = {
                chart: {
                    renderTo: 'seoul',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'

                },
                title: {
                    text: 'Seoul Voting Result'
                },
                tooltip: {
                    formatter: function() {
                        return '<b>' + this.point.name + '</b>: ' + this.percentage + ' %' + '<br>  (' + this.y + '표' + ')';;
                    }
                },
                
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            formatter: function() {
                                return '<b>' + this.point.name + '</b>: ' + this.percentage.toFixed(2) + ' %' + '<br>  (' + this.y + '표' + ')';
                            }
                        },
                        showInLegend: true
                    }
                },
                series: [{}]
            }

            $.getJSON("../seoul.php", function(json) {
                options.series[0] = json;                
                //options.series[0] = json;

                chart = new Highcharts.Chart(options);
            });

        });
        
        
        
        /***************************************************************************************************/
        
        $(document).ready(function() {
            var options = {
                chart: {
                    renderTo: 'busan',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'

                },
                title: {
                    text: 'Busan Voting Result'
                },
                tooltip: {
                    formatter: function() {
                        return '<b>' + this.point.name + '</b>: ' + this.percentage + ' %' + '<br>  (' + this.y + '표' + ')';;
                    }
                },
                
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            formatter: function() {
                                return '<b>' + this.point.name + '</b>: ' + this.percentage.toFixed(2) + ' %' + '<br>  (' + this.y + '표' + ')';
                            }
                        },
                        showInLegend: true
                    }
                },
                series: [{}]
            }

            $.getJSON("../busan.php", function(json) {
                options.series[0] = json;                
                //options.series[0] = json;

                chart = new Highcharts.Chart(options);
            });

        });
        
        
        /*******************************************************************************************/
        
        $(document).ready(function() {
            var options = {
                chart: {
                    renderTo: 'daejeon',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'

                },
                title: {
                    text: 'Daejeon Voting Result'
                },
                tooltip: {
                    formatter: function() {
                        return '<b>' + this.point.name + '</b>: ' + this.percentage + ' %' + '<br>  (' + this.y + '표' + ')';;
                    }
                },
                
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            formatter: function() {
                                return '<b>' + this.point.name + '</b>: ' + this.percentage.toFixed(2) + ' %' + '<br>  (' + this.y + '표' + ')';
                            }
                        },
                        showInLegend: true
                    }
                },
                series: [{}]
            }

            $.getJSON("../daejeon.php", function(json) {
                options.series[0] = json;                
                //options.series[0] = json;

                chart = new Highcharts.Chart(options);
            });

        });
        
        
         /*******************************************************************************************/
        
        $(document).ready(function() {
            var options = {
                chart: {
                    renderTo: 'gwangju',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'

                },
                title: {
                    text: 'Gwangju Voting Result'
                },
                tooltip: {
                    formatter: function() {
                        return '<b>' + this.point.name + '</b>: ' + this.percentage + ' %' + '<br>  (' + this.y + '표' + ')';;
                    }
                },
                
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            formatter: function() {
                                return '<b>' + this.point.name + '</b>: ' + this.percentage.toFixed(2) + ' %' + '<br>  (' + this.y + '표' + ')';
                            }
                        },
                        showInLegend: true
                    }
                },
                series: [{}]
            }

            $.getJSON("../gwangju.php", function(json) {
                options.series[0] = json;                
                //options.series[0] = json;

                chart = new Highcharts.Chart(options);
            });

        });
        
        
    </script>
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script src="http://code.highcharts.com/modules/exporting.js"></script>
	</head>
	<body>

		<!-- Header -->
			<header id="header">
				<h1>KBK_Election</h1>
				<nav>
					<ul>
						<li><a href="#intro">Total Result</a></li>
						<li><a href="#seoul">Seoul</a></li>
						<li><a href="#busan">Busan</a></li>
						<li><a href="#daejeon">Daejeon</a></li>
						<li><a href="#gwangju">GwangJu</a></li>
					</ul>
				</nav>
			</header>

		<!-- Total -->
			<section id="intro" >
				<div id="total" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
			</section>

		<!-- Seoul -->
			<section id="seoul" >
                <div id="seoul" style="min-width: 400px; height: 400px; margin: 0 auto"></div>

			</section>

		<!-- Two -->
			<section id="busan" >
				 <div id="busan" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
			</section>

		<!-- Work -->
			<section id="daejeon" >
				 <div id="daejeon" style="min-width: 400px; height: 400px; margin: 0 auto"></div>

			</section>

		<!-- Contact -->
			<section id="gwangju">
				 <div id="gwangju" style="min-width: 400px; height: 400px; margin: 0 auto"></div>

			</section>

		<!-- Footer -->
			<footer id="footer">

				<!-- Icons -->
					<ul class="actions">
						<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
						<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
						<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
						<li><a href="#" class="icon fa-linkedin"><span class="label">LinkedIn</span></a></li>
						<li><a href="#" class="icon fa-dribbble"><span class="label">Dribbble</span></a></li>
						<li><a href="#" class="icon fa-pinterest"><span class="label">Pinterest</span></a></li>
					</ul>

				<!-- Menu -->
					<ul class="menu">
						<li>&copy; Untitled</li><li>Design: <a href="https://html5up.net">HTML5 UP</a></li>
					</ul>

			</footer>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.poptrox.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>
