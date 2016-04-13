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
							  <li class="active dropdown">
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
					  <li><a href="./uptime.php"><i class="icon-hdd"></i>&nbsp;Uptime</a></li>
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
				<h3>Year</h3>

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
				<li class="active"><a href="#unique_t">Stations</a></li>
				<li><a href="#duration_t">Duration</a></li>
				<li><a href="#visit_t">Probe requests</a></li>
				</ul>
				<div class="tab-content">
				<div class="tab-pane active" id="unique_t">
					<div id="chart_unique" style="width:100%; height:400px;"></div><br/>
					<div id="chart_unique_new-return" style="width:1170px; height:400px;"></div>
				</div>
				<div class="tab-pane" id="duration_t">
					<div id="chart_average_duration" style="width:1170px; height:400px;"></div>
				</div>
				<div class="tab-pane" id="visit_t">
					<div id="chart_visit" style="width:1170px; height:400px;"></div><br/>
					<div id="chart_visit_pineapple" style="width:1170px; height:400px;"></div><br/>
					<div id="chart_new_vs_return_visit" style="width:1170px; height:400px;"></div>
				</div>
				</div>
			</div>
		</div>
				  
			<?php
			
				if(login_check($mysqli) == true)
				{
					//Unique Stations
					$result = $mysqli->query("
						SELECT
					 		DATE_FORMAT(`Data_Timestamp`,'%Y') AS `Unique_Date`,
							COUNT(DISTINCT `Station_MAC`) AS `Unique_Number` 
						FROM `PineappleStats_Data` 
						WHERE
							DATE_FORMAT(`Data_Timestamp`,'%Y-%m-%d') >= '".$start_date."' AND
							DATE_FORMAT(`Data_Timestamp`,'%Y-%m-%d') <= '".$end_date."'
							".$pineapple_filter."
						GROUP BY `Unique_Date`
					 	ORDER BY `Unique_Date`
					");
					
					$uniques=array();
					
					while($row = $result->fetch_assoc())
					{
						$uniques[strtotime($row['Unique_Date'])*1000] = $row['Unique_Number'];
					}
					
					// Visit
					$result = $mysqli->query("
						SELECT 
							DATE_FORMAT(`Data_Timestamp`,'%Y') AS `Visit_Date`,
							COUNT(*) AS `Visit_Number` 
						FROM `PineappleStats_Data` 
						WHERE
							DATE_FORMAT(`Data_Timestamp`,'%Y-%m-%d') >= '".$start_date."' AND
							DATE_FORMAT(`Data_Timestamp`,'%Y-%m-%d') <= '".$end_date."'
							".$pineapple_filter." 
						GROUP BY `Visit_Date`
						ORDER BY `Visit_Date`
					");
					
					$visits=array();
					
					while($row = $result->fetch_assoc())
					{
						$visits[strtotime($row['Visit_Date'])*1000] = $row['Visit_Number'];
					}
					
					// Visit per Pineapple
					$result = $mysqli->query("
						SELECT 
							DATE_FORMAT(`Data_Timestamp`,'%Y') AS `Visit_Date`,
							`Pineapple_Name`,
							COUNT(*) AS `Visit_Number` 
						FROM `PineappleStats_Data_View` 
						WHERE
							DATE_FORMAT(`Data_Timestamp`,'%Y-%m-%d') >= '".$start_date."' AND
							DATE_FORMAT(`Data_Timestamp`,'%Y-%m-%d') <= '".$end_date."'
							".$pineapple_filter." 
						GROUP BY `Visit_Date`, `Pineapple_Name`
					");
					
					$visits_pineapple=array();
					
					while($row = $result->fetch_assoc())
					{
						$visits_pineapple[$row['Pineapple_Name']][strtotime($row['Visit_Date'])*1000] = $row['Visit_Number'];
					}
					
					//Average Visit Duration
					$result = $mysqli->query("
						SELECT `Average_Visit_Date`, 
								AVG(`duration`) AS Average_Visit_Duration 
						FROM ( 
							SELECT 
								DATE_FORMAT(`Data_Timestamp`,'%Y') as Average_Visit_Date, 
								`Station_MAC` AS `session`, 
								MIN(`Data_Timestamp`) AS start_time, 
								MAX(`Data_Timestamp`) AS finish_time, 
								UNIX_TIMESTAMP(MAX(`Data_Timestamp`)) - UNIX_TIMESTAMP(MIN(`Data_Timestamp`)) AS duration 
							FROM `PineappleStats_Data` 
							WHERE
								DATE_FORMAT(`Data_Timestamp`,'%Y-%m-%d') >= '".$start_date."' AND
								DATE_FORMAT(`Data_Timestamp`,'%Y-%m-%d') <= '".$end_date."'
								".$pineapple_filter."
							GROUP BY Average_Visit_Date, `session` )v 
						GROUP BY `Average_Visit_Date` 
						ORDER BY `Average_Visit_Date`
					");
					
					$average_visits=array();
					
					while($row = $result->fetch_assoc())
					{
						$average_visits[strtotime($row['Average_Visit_Date'])*1000] = $row['Average_Visit_Duration'];
					}
					
					// New vs Returning
					$result = $mysqli->query("
						SELECT
							DATE_FORMAT(`Data_Timestamp`,'%Y') as Visit_Date,
							SUM(new_visitor) AS new_visits,
							SUM(returning_visitor) AS returning_visits,
							SUM(new_visitor) + SUM(returning_visitor) AS total
						FROM
						(
							SELECT
								PineappleStats_Data_View.`Station_MAC`,
								PineappleStats_Data_View.`Data_Timestamp`,
								_min,
								IF(UNIX_TIMESTAMP(`Data_Timestamp`) - UNIX_TIMESTAMP(_min) < 18000,1,0) AS  new_visitor,
								IF(UNIX_TIMESTAMP(`Data_Timestamp`) - UNIX_TIMESTAMP(_min) > 18000,1,0) AS  returning_visitor
								FROM PineappleStats_Data_View
								INNER JOIN 
								(
									SELECT 
									DATE_FORMAT(`Data_Timestamp`,'%Y') as Visit_Date,
									`Station_MAC`,
									MIN(`Data_Timestamp`) AS _min 
									FROM PineappleStats_Data_View
									WHERE
										DATE_FORMAT(`Data_Timestamp`,'%Y-%m-%d') >= '".$start_date."' AND
										DATE_FORMAT(`Data_Timestamp`,'%Y-%m-%d') <= '".$end_date."'
										".$pineapple_filter."
									GROUP BY Visit_Date, `Station_MAC`
								) AS min ON min.`Station_MAC` = PineappleStats_Data_View.`Station_MAC` and min.Visit_Date = DATE_FORMAT(PineappleStats_Data_View.`Data_Timestamp`,'%Y')
								WHERE
									DATE_FORMAT(`Data_Timestamp`,'%Y-%m-%d') >= '".$start_date."' AND
									DATE_FORMAT(`Data_Timestamp`,'%Y-%m-%d') <= '".$end_date."'
									".$pineapple_filter."
								ORDER BY PineappleStats_Data_View.`Station_MAC`, PineappleStats_Data_View.`Data_Timestamp`
						) v
						GROUP BY Visit_Date
						ORDER BY Visit_Date
					");
				
					$new_vs_returning_visits=array();
				
					while($row = $result->fetch_assoc())
					{
						$new_vs_returning_visits[$row['Visit_Date']] = array($row['new_visits'],$row['returning_visits']);
					}
				
					// New vs Returning
					$result = $mysqli->query("
						SELECT
							`Visit_Date`,
							SUM(new_visitor) AS new_visits,
							SUM(returning_visitor) AS returning_visits
						FROM 
						(
							SELECT
								DATE_FORMAT(`Stats_Timestamp`,'%Y') AS `Visit_Date`,
								CONCAT(`Station_MAC`, \"-\", `Station_X`) AS `session`,
								IF(`Station_X` = 1, 1, 0)  AS new_visitor,
								IF(`Station_X` >1, 1,0) AS returning_visitor
							FROM `PineappleStats_Stats`
							WHERE
								DATE_FORMAT(`Stats_Timestamp`,'%Y-%m-%d') >= '".$start_date."' AND
								DATE_FORMAT(`Stats_Timestamp`,'%Y-%m-%d') <= '".$end_date."'
								".$pineapple_filter."
							GROUP BY `Visit_Date`,`session` 
						) v
						GROUP BY `Visit_Date`
						ORDER BY `Visit_Date`
					");
				
					$new_vs_returning_unique_visits=array();
				
					while($row = $result->fetch_assoc())
					{
						$new_vs_returning_unique_visits[$row['Visit_Date']] = array($row['new_visits'],$row['returning_visits']);
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
		
		
		// chart_unique
		$(function () {
			
			Highcharts.setOptions({
	            global: {
	                useUTC: false
	            }
	        });
			
	        $('#chart_unique').highcharts({
	            chart: {
	                type: 'column'
	            },
	            title: {
	                text: 'Number of unique Stations'
	            },
				xAxis: {
			        type: 'datetime',
					labels: {
			            formatter: function() {
			                return Highcharts.dateFormat('%Y', this.value);
			            }
			        }
			    },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'uniques'
	                }
	            },
	            tooltip: {
	                formatter: function() {
	                    return '<b>'+ Highcharts.dateFormat('%Y', this.x) +'</b><br/>'+
                        this.series.name +': '+ this.y;
	                }
	            },
	            series: [{
	                name: 'unique Stations',
	                data: [
					<?php
					
						$i=1; $all=count($uniques);
						foreach($uniques as $key => $value)
						{
							echo "[".$key.", ".$value."]";
							if($i != $all) echo ",";
							
							$i++;
						}
					?>
	                ],
					dataLabels: {
	                    enabled: true,
	                    rotation: -90,
	                    color: '#FFFFFF',
	                    align: 'right',
	                    x: 4,
	                    y: 10,
	                    style: {
	                        fontSize: '13px',
	                        fontFamily: 'Verdana, sans-serif'
	                    }
	                }
	            }]
	        });
	    });
		
		// chart_new_vs_returning_visit
		$(function () {
			
			Highcharts.setOptions({
	            global: {
	                useUTC: false
	            }
	        });
			
	        $('#chart_new_vs_return_visit').highcharts({
	            chart: {
	                type: 'column'
	            },
	            title: {
	                text: 'New vs Returning probe requests'
	            },
				xAxis: {
					categories:[ 
					<?php
				
						$i=1; $all=count($new_vs_returning_visits);
						foreach($new_vs_returning_visits as $key => $value)
						{
							echo "'".$key."'";
							if($i != $all) echo ",";
						
							$i++;
						}
					?>
					]
			    },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'probe requests'
	                },
					stackLabels: {
	                    enabled: true,
	                    style: {
	                        fontWeight: 'bold',
	                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
	                    }
	                }
	            },
				legend: {
	                align: 'right',
	                x: -100,
	                verticalAlign: 'top',
	                y: 20,
	                floating: true,
	                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
	                borderColor: '#CCC',
	                borderWidth: 1,
	                shadow: false
	            },
	            tooltip: {
	                formatter: function() {
	                    return '<b>'+ this.x +'</b><br/>'+
                        this.series.name +': '+ this.y +' ('+ Highcharts.numberFormat((this.y/this.point.stackTotal)*100, 1) +'%)<br/>'+
	                        'Total: '+ this.point.stackTotal;
	                }
	            },
				plotOptions: {
	                column: {
	                    stacking: 'normal',
	                    dataLabels: {
	                        enabled: true,
	                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
	                    }
	                }
	            },
	            series: [
                {
					name: 'returning',
	                data: [
					<?php
				
						$i=1; $all=count($new_vs_returning_visits);
						foreach($new_vs_returning_visits as $key => $value)
						{
							echo "".$value[1]."";
							if($i != $all) echo ",";
						
							$i++;
						}
					?>
					]
	            },{
	                name: 'new',
	                data: [
					<?php
					
						$i=1; $all=count($new_vs_returning_visits);
						foreach($new_vs_returning_visits as $key => $value)
						{
							echo "".$value[0]."";
							if($i != $all) echo ",";
							
							$i++;
						}
					?>
					]
	            }]
	        });
	    });
		
		// chart_visit
		$(function () {
			
			Highcharts.setOptions({
	            global: {
	                useUTC: false
	            }
	        });
			
	        $('#chart_visit').highcharts({
	            chart: {
	                type: 'column'
	            },
	            title: {
	                text: 'Number of Stations probe requests'
	            },
				xAxis: {
			        type: 'datetime',
					labels: {
			            formatter: function() {
			                return Highcharts.dateFormat('%Y', this.value);
			            }
			        }
			    },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'probe requests'
	                }
	            },
	            tooltip: {
	                formatter: function() {
	                    return '<b>'+ Highcharts.dateFormat('%Y', this.x) +'</b><br/>'+
                        this.series.name +': '+ this.y;
	                }
	            },
	            series: [{
	                name: 'probe requests',
	                data: [
					<?php
					
						$i=1; $all=count($visits);
						foreach($visits as $key => $value)
						{
							echo "[".$key.", ".$value."]";
							if($i != $all) echo ",";
							
							$i++;
						}
					?>
	                ],
					dataLabels: {
	                    enabled: true,
	                    rotation: -90,
	                    color: '#FFFFFF',
	                    align: 'right',
	                    x: 4,
	                    y: 10,
	                    style: {
	                        fontSize: '13px',
	                        fontFamily: 'Verdana, sans-serif'
	                    }
	                }
	            }]
	        });
	    });
		
		// chart_new_vs_returning_unique_visit
		$(function () {
			
			Highcharts.setOptions({
	            global: {
	                useUTC: false
	            }
	        });
			
	        $('#chart_unique_new-return').highcharts({
	            chart: {
	                type: 'column'
	            },
	            title: {
	                text: 'New vs Returning unique Stations'
	            },
				xAxis: {
					categories:[ 
					<?php
				
						$i=1; $all=count($new_vs_returning_unique_visits);
						foreach($new_vs_returning_unique_visits as $key => $value)
						{
							echo "'".$key."'";
							if($i != $all) echo ",";
						
							$i++;
						}
					?>
					]
			    },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'uniques'
	                },
					stackLabels: {
	                    enabled: true,
	                    style: {
	                        fontWeight: 'bold',
	                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
	                    }
	                }
	            },
				legend: {
	                align: 'right',
	                x: -100,
	                verticalAlign: 'top',
	                y: 20,
	                floating: true,
	                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
	                borderColor: '#CCC',
	                borderWidth: 1,
	                shadow: false
	            },
	            tooltip: {
	                formatter: function() {
	                    return '<b>'+ this.x +'</b><br/>'+
                        this.series.name +': '+ this.y +' ('+ Highcharts.numberFormat((this.y/this.point.stackTotal)*100, 1) +'%)<br/>'+
	                        'Total: '+ this.point.stackTotal;
	                }
	            },
				plotOptions: {
	                column: {
	                    stacking: 'normal',
	                    dataLabels: {
	                        enabled: true,
	                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
	                    }
	                }
	            },
	            series: [
                {
					name: 'returning',
	                data: [
					<?php
				
						$i=1; $all=count($new_vs_returning_unique_visits);
						foreach($new_vs_returning_unique_visits as $key => $value)
						{
							echo "".$value[1]."";
							if($i != $all) echo ",";
						
							$i++;
						}
					?>
					]
	            },{
	                name: 'new',
	                data: [
					<?php
					
						$i=1; $all=count($new_vs_returning_unique_visits);
						foreach($new_vs_returning_unique_visits as $key => $value)
						{
							echo "".$value[0]."";
							if($i != $all) echo ",";
							
							$i++;
						}
					?>
					]
	            }]
	        });
	    });

		// chart_average_duration
		$(function () {
			
			Highcharts.setOptions({
	            global: {
	                useUTC: false
	            }
	        });
			
	        $('#chart_average_duration').highcharts({
	            chart: {
	                type: 'line'
	            },
	            title: {
	                text: 'Average Stations duration'
	            },
				xAxis: {
			        type: 'datetime',
					labels: {
			            formatter: function() {
			                return Highcharts.dateFormat('%Y', this.value);
			            }
			        }
			    },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'secondes'
	                }
	            },
	            tooltip: {
	                formatter: function() {
	                    return '<b>'+ Highcharts.dateFormat('%Y', this.x) +'</b><br/>'+
                        this.series.name +': '+ this.y;
	                }
	            },
				plotOptions: {
	                line: {
	                    dataLabels: {
	                        enabled: true
	                    },
	                    enableMouseTracking: true
	                }
	            },
	            series: [ {
	                name: 'average duration',
	                data: [
					<?php
				
						$i=1; $all=count($average_visits);
						foreach($average_visits as $key => $value)
						{
							echo "[".$key.", ".$value."]";
							if($i != $all) echo ",";
						
							$i++;
						}
					?>
					]
				}]
	        });
	    });
		
		// chart_visit_pineapple
		$(function () {
			
			Highcharts.setOptions({
	            global: {
	                useUTC: false
	            }
	        });
			
	        $('#chart_visit_pineapple').highcharts({
	            chart: {
	                type: 'line'
	            },
	            title: {
	                text: 'Number of Stations probe requests per Pineapple'
	            },
				xAxis: {
			        type: 'datetime',
					labels: {
			            formatter: function() {
			                return Highcharts.dateFormat('%Y', this.value);
			            }
			        }
			    },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'probe requests'
	                }
	            },
	            tooltip: {
	                formatter: function() {
	                    return '<b>'+ Highcharts.dateFormat('%Y', this.x) +'</b><br/>'+
                        this.series.name +': '+ this.y;
	                }
	            },
				plotOptions: {
	                line: {
	                    dataLabels: {
	                        enabled: true
	                    },
	                    enableMouseTracking: true
	                }
	            },
	            series: [
					
					<?php
				
						$i=1; $all=count($visits_pineapple);
						foreach($visits_pineapple as $key => $value)
						{
							echo "{\n";
							echo "name: '".$key."',\n";
							echo "data: [\n";
							
							$j=1; $all_data=count($value); ksort($value);
							foreach($value as $key_timestamp => $value_signal)
							{
								echo "[".$key_timestamp.", ".$value_signal."]";
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

