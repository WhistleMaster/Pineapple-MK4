<?php 
	include('includes/db_connect.php'); 
	include('includes/functions.php'); 
	
	sec_session_start();
	
	if(login_check($mysqli) != true)
	{
		header('Location: ./');
	}
	
	if(isset($_GET['reset']))
	{
		unset($_SESSION['pineapple_filter']);
		unset($_SESSION['date_filter']);
		
		$pineapple_filter = "";
		$pineapple_filtered = "";
		
		$start_date = date("Y-m-d");
		$end_date = date("Y-m-d");
		
		$_SESSION['date_filter'] = $start_date.",".$end_date;
	}
	
	if(isset($_GET['pineapple']) && $_GET['pineapple'] != "" && $_GET['pineapple'] != "all")
	{
		$pineapple_filter = "AND Pineapple_ID ='".$mysqli->real_escape_string($_GET['pineapple'])."'";
		$_SESSION['pineapple_filter'] = $mysqli->real_escape_string($_GET['pineapple']);
		
		$pineapple_filtered = $mysqli->real_escape_string($_GET['pineapple']);
	}
	else if(isset($_GET['pineapple']) && $_GET['pineapple'] != "" && $_GET['pineapple'] == "all")
	{
		$pineapple_filter = "";
		unset($_SESSION['pineapple_filter']);
		
		$pineapple_filtered = "";
	}
	else if(isset($_SESSION['pineapple_filter']) && $_SESSION['pineapple_filter'] != "" && $_SESSION['pineapple_filter'] != "all")
	{
		$pineapple_filter = "AND Pineapple_ID ='".$_SESSION['pineapple_filter']."'";
		
		$pineapple_filtered = $_SESSION['pineapple_filter'];
	}
	else if(isset($_SESSION['pineapple_filter']) && $_SESSION['pineapple_filter'] != "" && $_SESSION['pineapple_filter'] == "all")
	{
		$pineapple_filter = "";
		$pineapple_filtered = "";
	}
	
	if(isset($_GET['date']) && $_GET['date'] != "")
	{
		$dates = explode(',', $mysqli->real_escape_string($_GET['date']));
		
		$start_date = isset($dates[0]) ? $dates[0] : "";
		$end_date = isset($dates[1]) ? $dates[1] : "";
		
		$_SESSION['date_filter'] = $_GET['date'];
	}
	else if(isset($_SESSION['date_filter']) && $_SESSION['date_filter'] != "")
	{
		$dates = explode(',', $_SESSION['date_filter']);
		
		$start_date = isset($dates[0]) ? $dates[0] : "";
		$end_date = isset($dates[1]) ? $dates[1] : "";
	}
	else
	{
		$start_date = date("Y-m-d");
		$end_date = date("Y-m-d");
		
		$_SESSION['date_filter'] = $start_date.",".$end_date;
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $conf['name']; ?> - v<?php echo $conf['version']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="css/table_jui.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/jquery-ui-1.8.4.custom.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/ColReorder.css" media="screen" rel="stylesheet" type="text/css" />

<!-- Bootstrap -->

<style>
@import url('css/bootstrap.min.css');
@import url('css/bootstrap-responsive.min.css');
@import url('css/style.css');
@import url('css/daterangepicker.css');
</style>

</head>
<body>
	<div class="navbar navbar navbar-fixed-top">
	      <div class="navbar-inner">
	        <div class="container">
	          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	            <span class="icon-bar"></span>
	          </button>
	          <a class="brand" href="./"><?php echo $conf['name']; ?> <span style="font-size: 0.5em;">v<?php echo $conf['version']; ?></span></a>
	          <div class="nav-collapse collapse">
				  <ul class="nav">
					  <li><a href="./pineapple.php"><i class="icon-fire"></i>&nbsp;Pineapples</a></li>
					  <li><a href="./ssid.php"><i class="icon-signal"></i>&nbsp;SSID</a></li>
					  <li><a href="./stations.php"><i class="icon-leaf"></i>&nbsp;Stations</a></li>
					  <li><a href="./map.php"><i class="icon-globe"></i>&nbsp;Map</a></li>
					  <li>
						  <ul class="nav pull-right">
							  <li class="dropdown">
								  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-list-alt"></i>&nbsp;Stats <b class="caret"></b></a>
								  <ul class="dropdown-menu">
									  <li><a href="./stats_day.php"><i class="icon-search"></i>&nbsp;Day</a></li>
									  <li><a href="./stats_week.php"><i class="icon-search"></i>&nbsp;Week</a></li>
									  <li><a href="./stats_month.php"><i class="icon-search"></i>&nbsp;Month</a></li>
									  <li><a href="./stats_quarter.php"><i class="icon-search"></i>&nbsp;Quarter</a></li>
									  <li><a href="./stats_year.php"><i class="icon-search"></i>&nbsp;Year</a></li>
								  </ul>
							  </li>
						  </ul>
					  </li>
					  <li class="active"><a href="./uptime.php"><i class="icon-hdd"></i>&nbsp;Uptime</a></li>
					  <li><a href="./tools.php"><i class="icon-wrench"></i>&nbsp;Tools</a></li>
				  </ul>
				  <ul class="nav pull-right">
					  <li class="dropdown">
						  <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-certificate"></i>&nbsp;Pineapple <b class="caret"></b></a>
						  <ul class="dropdown-menu">
							  <?php
								  if(isset($pineapple_filtered) && $pineapple_filtered != "")
									  echo '<li><a href="?pineapple=all">All</a></li>';
								  else
									  echo '<li><a href="?pineapple=all"><i class="icon-ok"></i>&nbsp;All</a></li>';
								  ?>
								  <li class="divider"></li>
								  <?php
			
								  $result = $mysqli->query("SELECT * FROM PineappleStats_Pineapples");
								  while($row = $result->fetch_assoc())
								  {
									  if(isset($pineapple_filtered) && $pineapple_filtered == $row['Pineapple_ID'])
										  echo '<li><a href="?pineapple='.$row['Pineapple_ID'].'"><i class="icon-ok"></i>&nbsp;'.$row['Pineapple_Name'].'</a></li>';
									  else
										  echo '<li><a href="?pineapple='.$row['Pineapple_ID'].'">'.$row['Pineapple_Name'].'</a></li>';
								  }
							?>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i>&nbsp;<?php echo $_SESSION['username']; ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li class="nav-header">Account</li>
							<li><a href="./settings.php"><i class="icon-pencil"></i>&nbsp;Settings</a></li>
							<li class="divider"></li>
							<li><a href="logout.php"><i class="icon-off"></i>&nbsp;Logout</a></li>
						</ul>
					</li>
				</ul>				  
			  </div><!--/.nav-collapse -->
		  </div>
	  </div>
	</div>

	    <div class="container">
			<div class="row">
				<div class="span12">
					<h3>Uptime</h3>

					<div id="reportrange" class="pull-right">
					    <i class="icon-calendar icon-large"></i>
					    <span><?php echo date("F j, Y",strtotime($start_date))." - ".date("F j, Y",strtotime($end_date)); ?></span> <b class="caret"></b>
					</div>
					<button style="margin-bottom: 10px;" class="btn btn-info" type="button" onclick="javascript:resetFilters();"><i class="icon-refresh icon-white"></i> Reset Filter(s)</button>
			
				</div>
			</div>
		</div>
		<div class="container well">
			
			<div class="row">
				<div class="span12">
					<ul class="nav nav-tabs" style="margin-bottom: 0;" id="myTab">
					<li class="active"><a href="#uptime_t">Uptime</a></li>
					<li><a href="#report_t">Report</a></li>
				</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="uptime_t">
							<div class="row">
								<div class="span12">
									<div id="chart_hours" style="width:1170px; height:400px;"></div>
								</div>
							</div>
			
							<div class="row">
								<div class="span12">
									<div id="chart_day" style="width:1170px; height:400px;"></div>
								</div>
							</div>

							<div class="row">
								<div class="span12">
									<div id="chart_week" style="width:1170px; height:400px;"></div>
								</div>
							</div>
			
							<div class="row">
								<div class="span12">
									<div id="chart_month" style="width:1170px; height:400px;"></div>
								</div>
							</div>
			
							<div class="row">
								<div class="span12">
									<div id="chart_quarter" style="width:1170px; height:400px;"></div>
								</div>
							</div>
			
							<div class="row">
								<div class="span12">
									<div id="chart_year" style="width:1170px; height:400px;"></div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="report_t">
							<div class="row">
								<div class="span12">
									<div id="chart_report" style="width:1170px; height:400px;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			  
			<?php
			
				if(login_check($mysqli) == true)
				{
					//uptime - hours
					$result = $mysqli->query("
						SELECT   
							DATE_FORMAT(A.`Pineapple_LastReport` , '%Y-%m-%d' ) AS Uptime_Date,
							CONCAT(HOUR(A.`Pineapple_LastReport`), ':00-', HOUR(A.`Pineapple_LastReport`)+1, ':00') AS Uptime_Hours,
							( 
								SELECT B.`Pineapple_Name`
								FROM PineappleStats_Pineapples B
								WHERE B.`Pineapple_ID` = A.`Pineapple_ID` 
							) AS Pineapple_Name,
							COUNT(A.Uptime_ID) AS Uptime_Count
						FROM PineappleStats_Uptime A
						WHERE 
							DATE_FORMAT(A.`Pineapple_LastReport`,'%Y-%m-%d') >= '".date("Y-m-d")."' AND
							DATE_FORMAT(A.`Pineapple_LastReport`,'%Y-%m-%d') <= '".date("Y-m-d")."'
						".$pineapple_filter."
						GROUP BY Uptime_Date, HOUR(A.`Pineapple_LastReport`)
					");
					
					$uptime_hours=array();
					
					while($row = $result->fetch_assoc())
					{
						$uptime_hours[$row['Pineapple_Name']][$row['Uptime_Hours']] = ($row['Uptime_Count'] / 60)*100;
					}

					//uptime - day
					$result = $mysqli->query("
						SELECT 
							DATE_FORMAT( A.`Pineapple_LastReport` , '%Y-%m-%d' ) AS Uptime_Date, 
							( 
								SELECT B.`Pineapple_Name`
								FROM PineappleStats_Pineapples B
								WHERE B.`Pineapple_ID` = A.`Pineapple_ID` 
							) AS Pineapple_Name,
							COUNT( A.`Uptime_ID` ) AS Uptime_Count
						FROM PineappleStats_Uptime A
						WHERE 
							DATE_FORMAT(A.`Pineapple_LastReport`,'%Y-%m-%d') >= '".$start_date."' AND
							DATE_FORMAT(A.`Pineapple_LastReport`,'%Y-%m-%d') <= '".$end_date."'
						".$pineapple_filter."
						GROUP BY A.`Pineapple_ID` , Uptime_Date
					");
					
					$uptime_day=array();
					
					while($row = $result->fetch_assoc())
					{
						$uptime_day[$row['Pineapple_Name']][strtotime($row['Uptime_Date'])*1000] = ($row['Uptime_Count'] / 1440)*100;
					}
					
					//uptime - week
					$result = $mysqli->query("
						SELECT 
							DATE_FORMAT( A.`Pineapple_LastReport` , '%Y-%u' ) AS Uptime_Date, 
							( 
								SELECT B.`Pineapple_Name`
								FROM PineappleStats_Pineapples B
								WHERE B.`Pineapple_ID` = A.`Pineapple_ID` 
							) AS Pineapple_Name,
							COUNT( A.`Uptime_ID` ) AS Uptime_Count
						FROM PineappleStats_Uptime A
						WHERE 
							DATE_FORMAT(A.`Pineapple_LastReport`,'%Y-%m-%d') >= '".$start_date."' AND
							DATE_FORMAT(A.`Pineapple_LastReport`,'%Y-%m-%d') <= '".$end_date."'
						".$pineapple_filter."
						GROUP BY A.`Pineapple_ID` , Uptime_Date
					");
					
					$uptime_week=array();
					
					while($row = $result->fetch_assoc())
					{
						$uptime_week[$row['Pineapple_Name']][$row['Uptime_Date']] = ($row['Uptime_Count'] / 10080)*100;
					}
					
					//uptime - month
					$result = $mysqli->query("
						SELECT 
							DATE_FORMAT( A.`Pineapple_LastReport` , '%Y-%m' ) AS Uptime_Date, 
							( 
								SELECT B.`Pineapple_Name`
								FROM PineappleStats_Pineapples B
								WHERE B.`Pineapple_ID` = A.`Pineapple_ID` 
							) AS Pineapple_Name,
							COUNT( A.`Uptime_ID` ) AS Uptime_Count
						FROM PineappleStats_Uptime A
						WHERE 
							DATE_FORMAT(A.`Pineapple_LastReport`,'%Y-%m-%d') >= '".$start_date."' AND
							DATE_FORMAT(A.`Pineapple_LastReport`,'%Y-%m-%d') <= '".$end_date."'
						".$pineapple_filter."
						GROUP BY A.`Pineapple_ID` , Uptime_Date
					");
					
					$uptime_month=array();
					
					while($row = $result->fetch_assoc())
					{
						$uptime_month[$row['Pineapple_Name']][$row['Uptime_Date']] = ($row['Uptime_Count'] / 43829)*100;
					}
					
					//uptime - quarter
					$result = $mysqli->query("
						SELECT 
							QUARTER(A.`Pineapple_LastReport`) AS Uptime_Date,
							( 
								SELECT B.`Pineapple_Name`
								FROM PineappleStats_Pineapples B
								WHERE B.`Pineapple_ID` = A.`Pineapple_ID` 
							) AS Pineapple_Name,
							COUNT( A.`Uptime_ID` ) AS Uptime_Count
						FROM PineappleStats_Uptime A
						WHERE 
							DATE_FORMAT(A.`Pineapple_LastReport`,'%Y-%m-%d') >= '".$start_date."' AND
							DATE_FORMAT(A.`Pineapple_LastReport`,'%Y-%m-%d') <= '".$end_date."'
						".$pineapple_filter."
						GROUP BY A.`Pineapple_ID` , Uptime_Date
					");
					
					$uptime_quarter=array();
					
					while($row = $result->fetch_assoc())
					{
						$uptime_quarter[$row['Pineapple_Name']][$row['Uptime_Date']] = ($row['Uptime_Count'] / 175316)*100;
					}
					
					//uptime - year
					$result = $mysqli->query("
						SELECT 
							DATE_FORMAT( A.`Pineapple_LastReport` , '%Y' ) AS Uptime_Date, 
							( 
								SELECT B.`Pineapple_Name`
								FROM PineappleStats_Pineapples B
								WHERE B.`Pineapple_ID` = A.`Pineapple_ID` 
							) AS Pineapple_Name,
							COUNT( A.`Uptime_ID` ) AS Uptime_Count
						FROM PineappleStats_Uptime A
						WHERE 
							DATE_FORMAT(A.`Pineapple_LastReport`,'%Y-%m-%d') >= '".$start_date."' AND
							DATE_FORMAT(A.`Pineapple_LastReport`,'%Y-%m-%d') <= '".$end_date."'
						".$pineapple_filter."
						GROUP BY A.`Pineapple_ID` , Uptime_Date
					");
					
					$uptime_year=array();
					
					while($row = $result->fetch_assoc())
					{
						$uptime_year[$row['Pineapple_Name']][$row['Uptime_Date']] = ($row['Uptime_Count'] / 525948)*100;
					}
					
					//uptime - report
					$result = $mysqli->query("
						SELECT 
							DATE_FORMAT( A.`Pineapple_LastReport` , '%Y-%m-%d %k:%i' ) AS Uptime_Date, 
							( 
								SELECT B.`Pineapple_Name`
								FROM PineappleStats_Pineapples B
								WHERE B.`Pineapple_ID` = A.`Pineapple_ID` 
							) AS Pineapple_Name,
							COUNT( A.`Uptime_ID` ) AS Uptime_Count
						FROM PineappleStats_Uptime A
						WHERE 
							DATE_FORMAT(A.`Pineapple_LastReport`,'%Y-%m-%d') >= '".$start_date."' AND
							DATE_FORMAT(A.`Pineapple_LastReport`,'%Y-%m-%d') <= '".$end_date."'
						".$pineapple_filter."
						GROUP BY A.`Pineapple_ID` , Uptime_Date
					");
					
					$uptime_report=array();
					
					while($row = $result->fetch_assoc())
					{
						$uptime_report[$row['Pineapple_Name']][strtotime($row['Uptime_Date'])*1000] = $row['Uptime_Count'];
					}
				}
				else
				{
					header('Location: ./');
				}
			
			?>
			
	    </div>
		
		<script src="js/jquery.js"></script>		
		<script src="js/bootstrap.min.js"></script>
		
        <script src="js/moment.min.js"></script>
        <script src="js/daterangepicker.js"></script>
		
		<script src="js/highcharts.js"></script>
		
		<script type="text/javascript" charset="utf-8">
		
		$('#reportrange').daterangepicker(
			{
				ranges: {
                   'Today': [new Date(), new Date()],
				   'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                   'Last 7 Days': [moment().subtract('days', 6), new Date()],
                   'Last 30 Days': [moment().subtract('days', 29), new Date()],
                   'This Month': [moment().startOf('month'), moment().endOf('month')],
                   'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                },
                opens: 'left',
                format: 'MM/DD/YYYY',
                separator: ' to ',
                startDate: new Date('<?php echo $start_date; ?>'),
                endDate: new Date('<?php echo $end_date; ?>'),
                minDate: '01/01/2012',
                maxDate: '12/31/2013',
                locale: {
                    applyLabel: 'Submit',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                },
                showWeekNumbers: true,
				showDropdowns: true,
                buttonClasses: ['btn-danger'],
                dateLimit: false
             },
             function(start, end) {
				if(start != null && end != null)
					window.location.href = '?date='+start.format('YYYY-MM-DD')+','+end.format('YYYY-MM-DD');
				else
					window.location.href = '?reset';
             }
		 );
		 
 	    $('#myTab a').click(function (e) {
 	    	e.preventDefault();
 	    	$(this).tab('show');
 	    })
		
		function resetFilters() {
			window.location.href = '?reset';
		}
		
		// chart - hours
		$(function () {
			
			Highcharts.setOptions({
	            global: {
	                useUTC: false
	            }
	        });
			
	        $('#chart_hours').highcharts({
				chart: {
	                zoomType: 'x',
	                spacingRight: 20
	            },
	            title: {
	                text: 'Hours (today)'
	            },
	            subtitle: {
	                text: document.ontouchstart === undefined ?
	                    'Click and drag in the plot area to zoom in' :
	                    'Pinch the chart to zoom in'
	            },
				xAxis: {
	                type: 'category'
	            },
	            yAxis: {
	                title: {
	                    text: 'Uptime %'
	                }
	            },
	            tooltip: {
	                shared: true,
	                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.1f}%</b><br/>'
	            },
				legend: {
	                layout: 'vertical',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 150,
	                y: 100,
	                floating: true,
	                borderWidth: 1,
	                backgroundColor: '#FFFFFF'
	            },
	            plotOptions: {
	                area: {
	                    fillColor: {
	                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
	                        stops: [
	                            [0, Highcharts.getOptions().colors[0]],
	                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
	                        ]
	                    },
	                    lineWidth: 1,
	                    marker: {
	                        enabled: false
	                    },
	                    shadow: false,
	                    states: {
	                        hover: {
	                            lineWidth: 1
	                        }
	                    },
	                    threshold: null
	                }
	            },
	            series: [
				
					<?php
			
						$i=1; $all=count($uptime_hours);
						foreach($uptime_hours as $key => $value)
						{
							echo "{\n";
							echo "type: 'area',\n";
							echo "name: '".$key."',\n";
							echo "data: [\n";
						
							$j=1; $all_data=count($value);
							foreach($value as $key_timestamp => $value_uptime)
							{
								echo "['".$key_timestamp."', ".$value_uptime."]";
								if($j != $all_data) echo ",\n";
					
								$j++;
							}
						
							echo "]\n";
							echo "}";
							if($i != $all) echo ",\n";
						
							$i++;
						}
					?>
				]
	        });
	    });
		
		// chart - day
		$(function () {
			
			Highcharts.setOptions({
	            global: {
	                useUTC: false
	            }
	        });
			
	        $('#chart_day').highcharts({
				chart: {
	                zoomType: 'x',
	                spacingRight: 20
	            },
	            title: {
	                text: 'Day'
	            },
	            subtitle: {
	                text: document.ontouchstart === undefined ?
	                    'Click and drag in the plot area to zoom in' :
	                    'Pinch the chart to zoom in'
	            },
				xAxis: {
	                type: 'datetime',
	                maxZoom: 14 * 24 * 3600000, // fourteen days
	                title: {
	                    text: null
	                }
	            },
	            yAxis: {
	                title: {
	                    text: 'Uptime %'
	                }
	            },
	            tooltip: {
	                shared: true,
					pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.1f}%</b><br/>'
	            },
				legend: {
	                layout: 'vertical',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 150,
	                y: 100,
	                floating: true,
	                borderWidth: 1,
	                backgroundColor: '#FFFFFF'
	            },
	            plotOptions: {
	                area: {
	                    fillColor: {
	                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
	                        stops: [
	                            [0, Highcharts.getOptions().colors[0]],
	                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
	                        ]
	                    },
	                    lineWidth: 1,
	                    marker: {
	                        enabled: false
	                    },
	                    shadow: false,
	                    states: {
	                        hover: {
	                            lineWidth: 1
	                        }
	                    },
	                    threshold: null
	                }
	            },
	            series: [
				
					<?php
			
						$i=1; $all=count($uptime_day);
						foreach($uptime_day as $key => $value)
						{
							echo "{\n";
							echo "type: 'area',\n";
							echo "name: '".$key."',\n";
							echo "data: [\n";
						
							$j=1; $all_data=count($value); ksort($value);
							foreach($value as $key_timestamp => $value_uptime)
							{
								echo "[".$key_timestamp.", ".$value_uptime."]";
								if($j != $all_data) echo ",\n";
					
								$j++;
							}
						
							echo "]\n";
							echo "}";
							if($i != $all) echo ",\n";
						
							$i++;
						}
					?>
				]
	        });
	    });
		
		// chart - week
		$(function () {
			
			Highcharts.setOptions({
	            global: {
	                useUTC: false
	            }
	        });
			
	        $('#chart_week').highcharts({
				chart: {
	                zoomType: 'x',
	                spacingRight: 20
	            },
	            title: {
	                text: 'Week'
	            },
	            subtitle: {
	                text: document.ontouchstart === undefined ?
	                    'Click and drag in the plot area to zoom in' :
	                    'Pinch the chart to zoom in'
	            },
				xAxis: {
	                type: 'category'
	            },
	            yAxis: {
	                title: {
	                    text: 'Uptime %'
	                }
	            },
	            tooltip: {
	                shared: true,
	                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.1f}%</b><br/>'
	            },
				legend: {
	                layout: 'vertical',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 150,
	                y: 100,
	                floating: true,
	                borderWidth: 1,
	                backgroundColor: '#FFFFFF'
	            },
	            plotOptions: {
	                area: {
	                    fillColor: {
	                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
	                        stops: [
	                            [0, Highcharts.getOptions().colors[0]],
	                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
	                        ]
	                    },
	                    lineWidth: 1,
	                    marker: {
	                        enabled: false
	                    },
	                    shadow: false,
	                    states: {
	                        hover: {
	                            lineWidth: 1
	                        }
	                    },
	                    threshold: null
	                }
	            },
	            series: [
				
					<?php
			
						$i=1; $all=count($uptime_week);
						foreach($uptime_week as $key => $value)
						{
							echo "{\n";
							echo "type: 'area',\n";
							echo "name: '".$key."',\n";
							echo "data: [\n";
						
							$j=1; $all_data=count($value); ksort($value);
							foreach($value as $key_timestamp => $value_uptime)
							{
								echo "['".$key_timestamp."', ".$value_uptime."]";
								if($j != $all_data) echo ",\n";
					
								$j++;
							}
						
							echo "]\n";
							echo "}";
							if($i != $all) echo ",\n";
						
							$i++;
						}
					?>
				]
	        });
	    });
		
		// chart - month
		$(function () {
			
			Highcharts.setOptions({
	            global: {
	                useUTC: false
	            }
	        });
			
	        $('#chart_month').highcharts({
				chart: {
	                zoomType: 'x',
	                spacingRight: 20
	            },
	            title: {
	                text: 'Month'
	            },
	            subtitle: {
	                text: document.ontouchstart === undefined ?
	                    'Click and drag in the plot area to zoom in' :
	                    'Pinch the chart to zoom in'
	            },
				xAxis: {
	                type: 'category'
	            },
	            yAxis: {
	                title: {
	                    text: 'Uptime %'
	                }
	            },
	            tooltip: {
	                shared: true,
	                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.1f}%</b><br/>'
	            },
				legend: {
	                layout: 'vertical',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 150,
	                y: 100,
	                floating: true,
	                borderWidth: 1,
	                backgroundColor: '#FFFFFF'
	            },
	            plotOptions: {
	                area: {
	                    fillColor: {
	                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
	                        stops: [
	                            [0, Highcharts.getOptions().colors[0]],
	                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
	                        ]
	                    },
	                    lineWidth: 1,
	                    marker: {
	                        enabled: false
	                    },
	                    shadow: false,
	                    states: {
	                        hover: {
	                            lineWidth: 1
	                        }
	                    },
	                    threshold: null
	                }
	            },
	            series: [
				
					<?php
			
						$i=1; $all=count($uptime_month);
						foreach($uptime_month as $key => $value)
						{
							echo "{\n";
							echo "type: 'area',\n";
							echo "name: '".$key."',\n";
							echo "data: [\n";
						
							$j=1; $all_data=count($value); ksort($value);
							foreach($value as $key_timestamp => $value_uptime)
							{
								echo "['".$key_timestamp."', ".$value_uptime."]";
								if($j != $all_data) echo ",\n";
					
								$j++;
							}
						
							echo "]\n";
							echo "}";
							if($i != $all) echo ",\n";
						
							$i++;
						}
					?>
				]
	        });
	    });
		
		// chart - quarter
		$(function () {
			
			Highcharts.setOptions({
	            global: {
	                useUTC: false
	            }
	        });
			
	        $('#chart_quarter').highcharts({
				chart: {
	                zoomType: 'x',
	                spacingRight: 20
	            },
	            title: {
	                text: 'Quarter'
	            },
	            subtitle: {
	                text: document.ontouchstart === undefined ?
	                    'Click and drag in the plot area to zoom in' :
	                    'Pinch the chart to zoom in'
	            },
				xAxis: {
	                type: 'category'
	            },
	            yAxis: {
	                title: {
	                    text: 'Uptime %'
	                }
	            },
	            tooltip: {
	                shared: true,
	                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.1f}%</b><br/>'
	            },
				legend: {
	                layout: 'vertical',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 150,
	                y: 100,
	                floating: true,
	                borderWidth: 1,
	                backgroundColor: '#FFFFFF'
	            },
	            plotOptions: {
	                area: {
	                    fillColor: {
	                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
	                        stops: [
	                            [0, Highcharts.getOptions().colors[0]],
	                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
	                        ]
	                    },
	                    lineWidth: 1,
	                    marker: {
	                        enabled: false
	                    },
	                    shadow: false,
	                    states: {
	                        hover: {
	                            lineWidth: 1
	                        }
	                    },
	                    threshold: null
	                }
	            },
	            series: [
				
					<?php
			
						$i=1; $all=count($uptime_quarter);
						foreach($uptime_quarter as $key => $value)
						{
							echo "{\n";
							echo "type: 'area',\n";
							echo "name: '".$key."',\n";
							echo "data: [\n";
						
							$j=1; $all_data=count($value); ksort($value);
							foreach($value as $key_timestamp => $value_uptime)
							{
								echo "['Q".$key_timestamp."', ".$value_uptime."]";
								if($j != $all_data) echo ",\n";
					
								$j++;
							}
						
							echo "]\n";
							echo "}";
							if($i != $all) echo ",\n";
						
							$i++;
						}
					?>
				]
	        });
	    });
		
		// chart - year
		$(function () {
			
			Highcharts.setOptions({
	            global: {
	                useUTC: false
	            }
	        });
			
	        $('#chart_year').highcharts({
				chart: {
	                zoomType: 'x',
	                spacingRight: 20
	            },
	            title: {
	                text: 'Year'
	            },
	            subtitle: {
	                text: document.ontouchstart === undefined ?
	                    'Click and drag in the plot area to zoom in' :
	                    'Pinch the chart to zoom in'
	            },
				xAxis: {
	                type: 'category'
	            },
	            yAxis: {
	                title: {
	                    text: 'Uptime %'
	                }
	            },
	            tooltip: {
	                shared: true,
	                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.1f}%</b><br/>'
	            },
				legend: {
	                layout: 'vertical',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 150,
	                y: 100,
	                floating: true,
	                borderWidth: 1,
	                backgroundColor: '#FFFFFF'
	            },
	            plotOptions: {
	                area: {
	                    fillColor: {
	                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
	                        stops: [
	                            [0, Highcharts.getOptions().colors[0]],
	                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
	                        ]
	                    },
	                    lineWidth: 1,
	                    marker: {
	                        enabled: false
	                    },
	                    shadow: false,
	                    states: {
	                        hover: {
	                            lineWidth: 1
	                        }
	                    },
	                    threshold: null
	                }
	            },
	            series: [
				
					<?php
			
						$i=1; $all=count($uptime_year);
						foreach($uptime_year as $key => $value)
						{
							echo "{\n";
							echo "type: 'area',\n";
							echo "name: '".$key."',\n";
							echo "data: [\n";
						
							$j=1; $all_data=count($value); ksort($value);
							foreach($value as $key_timestamp => $value_uptime)
							{
								echo "['".$key_timestamp."', ".$value_uptime."]";
								if($j != $all_data) echo ",\n";
					
								$j++;
							}
						
							echo "]\n";
							echo "}";
							if($i != $all) echo ",\n";
						
							$i++;
						}
					?>
				]
	        });
	    });
		
		// chart - report
		$(function () {
			
			Highcharts.setOptions({
	            global: {
	                useUTC: false
	            }
	        });
			
	        $('#chart_report').highcharts({
				chart: {
	                type: 'scatter',
					zoomType: 'x',
	                spacingRight: 20
	            },
	            title: {
	                text: 'Report'
	            },
	            subtitle: {
	                text: document.ontouchstart === undefined ?
	                    'Click and drag in the plot area to zoom in' :
	                    'Pinch the chart to zoom in'
	            },
				xAxis: {
	                type: 'datetime',
					ordinal: false,
	                maxZoom: 60 * 1000,
	                title: {
	                    text: null
	                },
					gridLineWidth: 1,
					minorGridLineWidth: 1
	            },
	            yAxis: {
	                title: {
	                    text: 'Uptime Report'
	                },
					lineWidth: 0,
					   minorGridLineWidth: 0,
					   lineColor: 'transparent',
					   gridLineColor: 'transparent',
					   labels: {
					       enabled: false
					   },
					   minorTickLength: 0,
					   tickLength: 0
	            },
				legend: {
	                layout: 'vertical',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 150,
	                y: 100,
	                floating: true,
	                borderWidth: 1,
	                backgroundColor: '#FFFFFF'
	            },
				plotOptions: {
	                scatter: {
	                    marker: {
	                        radius: 5,
	                        states: {
	                            hover: {
	                                enabled: true,
	                                lineColor: 'rgb(100,100,100)'
	                            }
	                        }
	                    },
	                    states: {
	                        hover: {
	                            marker: {
	                                enabled: false
	                            }
	                        }
	                    },
	                    tooltip: {
	                        pointFormat: '<strong>{point.x:%Y-%m-%d %H:%M}</strong>'
	                    }
	                }
	            },
	            series: [
				
					<?php
			
						$i=1; $all=count($uptime_report);
						foreach($uptime_report as $key => $value)
						{
							echo "{\n";
							//echo "type: 'area',\n";
							echo "name: '".$key."',\n";
							echo "pointInterval: 60 * 1000,";
                			echo "pointStart: new Date('<?php echo $start_date; ?>'),";
							echo "data: [\n";
						
							$j=1; $all_data=count($value); ksort($value);
							foreach($value as $key_timestamp => $value_uptime)
							{
								echo "[".$key_timestamp.", ".$value_uptime."]";
								if($j != $all_data) echo ",\n";
					
								$j++;
							}
						
							echo "]\n";
							echo "}";
							if($i != $all) echo ",\n";
						
							$i++;
						}
					?>
				]
	        });
	    });
		
		</script>
		
</body>
</html>

