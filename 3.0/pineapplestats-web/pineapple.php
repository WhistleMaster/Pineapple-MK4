<?php 
	include('includes/db_connect.php'); 
	include('includes/functions.php'); 
	
	sec_session_start();
	
	if(login_check($mysqli) != true)
	{
		header('Location: ./');
	}
	
	if(isset($_GET['pineapple']) && $_GET['pineapple'] != "" && $_GET['pineapple'] != "all")
	{
		$pineapple_filter = "WHERE Pineapple_ID ='".$mysqli->real_escape_string($_GET['pineapple'])."'";
		$_SESSION['pineapple_filter'] = $mysqli->real_escape_string($_GET['pineapple']);
		
		$pineapple_filtered = $mysqli->real_escape_string($_GET['pineapple']);
	}
	else if(isset($_GET['pineapple']) && $_GET['pineapple'] != "" && $_GET['pineapple'] == "all")
	{
		$pineapple_filter = "";
		$_SESSION['pineapple_filter'] = "";
		
		$pineapple_filtered = "";
	}
	else if(isset($_SESSION['pineapple_filter']) && $_SESSION['pineapple_filter'] != "" && $_SESSION['pineapple_filter'] != "all")
	{
		$pineapple_filter = "WHERE Pineapple_ID ='".$_SESSION['pineapple_filter']."'";
		
		$pineapple_filtered = $_SESSION['pineapple_filter'];
	}
	else if(isset($_SESSION['pineapple_filter']) && $_SESSION['pineapple_filter'] != "" && $_SESSION['pineapple_filter'] == "all")
	{
		$pineapple_filter = "";
		$pineapple_filtered = "";
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
	      	          <li class="active"><a href="./pineapple.php"><i class="icon-fire"></i>&nbsp;Pineapples <span id="online" class="badge badge-success">N/A</span> <span id="offline" class="badge badge-important">N/A</span></a></li>
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

	    <div class="container well">
			<div class="row">
				<div class="span6">
					<div id="chart_status" style="width:100%; height:400px;"></div>
				</div>
				<div class="span6">
					<div id="chart_location" style="width:100%; height:400px;"></div>
				</div>
			</div>

			<?php
					$result = $mysqli->query("
						SELECT 
							* 
						FROM 
							PineappleStats_Pineapples
						".$pineapple_filter."
					");
					
					?>
					<table cellpadding="0" cellspacing="0" border="0" class="display" id="grid" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>Number</th>
								<th>Name</th>
								<th>MAC</th>
								<th>Latitude</th>
								<th>Longitude</th>
								<th>Country</th>
								<th>First Report</th>
								<th>Last Report</th>
								<th>IP</th>
								<th>Uptime</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
		
						<?php	
							$offline_count = 0;
							$online_count = 0;
							
							$countries=array();
											
							while($row = $result->fetch_assoc())
							{								
								$Pineapple_LastReport = strtotime($row['Pineapple_LastReport']);
								
								$diff = strtotime("now") - $Pineapple_LastReport;
								
								$years   = floor($diff / (365*60*60*24)); 
								$months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
								$days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

								$hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
								$minutes  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
								$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60)); 
								
								if($Pineapple_LastReport < strtotime('-1 days'))
								{	$label = "label label-important"; $text = $days." day(s) Offline"; $offline_count++; }
								else if($Pineapple_LastReport < strtotime('-1 hours'))
								{	$label = "label label-warning"; $text = $hours." hour(s) Offline"; $offline_count++; }
								else 
								{	$label = "label label-success"; $text = "Online ".$minutes." minutes ago"; $online_count++; }
								
								echo '<tr>';
								
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_URL, "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$row['Pineapple_Latitude'].",".$row['Pineapple_Longitude']."&sensor=false");
								curl_setopt($ch, CURLOPT_HEADER, 0);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
								$geocode=curl_exec($ch);
								curl_close($ch);
		
								$output= json_decode($geocode);

							    for($j=0;$j<count($output->results[0]->address_components);$j++)
								{
									if($output->results[0]->address_components[$j]->types[0] == "country") 
									{	
										$countries[] = $Pineapple_Country = $output->results[0]->address_components[$j]->long_name;
									}
							    }
								
								$result_uptime = $mysqli->query("
									SELECT 
										`Pineapple_ID`,
										MIN(`Pineapple_LastReport`) AS FirstUptime,
										( COUNT(`Uptime_ID`) / (time_to_sec(timediff(now(), MIN(`Pineapple_LastReport`)))/60) ) * 100 AS Uptime
									FROM `PineappleStats_Uptime`
									WHERE `Pineapple_ID` = ".$row['Pineapple_ID']."
									GROUP BY `Pineapple_ID`
								");
								
								$row_uptime = $result_uptime->fetch_assoc();

								echo '<td>'.$row['Pineapple_ID'].'</td>';
							    echo '<td>'.$row['Pineapple_Number'].'</td>';
							    echo '<td>'.$row['Pineapple_Name'].'</td>';
							    echo '<td>'.$row['Pineapple_MAC'].'</td>';
							    echo '<td>'.$row['Pineapple_Latitude'].'</td>';
							    echo '<td>'.$row['Pineapple_Longitude'].'</td>';
							    echo '<td>'.$Pineapple_Country.'</td>';
								echo '<td>'.$row_uptime['FirstUptime'].'</td>';
							    echo '<td>'.$row['Pineapple_LastReport'].'</td>';
							    echo '<td>'.$row['Pineapple_IP'].'</td>';
								echo '<td>'.round($row_uptime['Uptime']).'%</td>';
							    echo '<td style="text-align: center;"><span class="'.$label.'">'.$text.'</span></td>';
								echo '</tr>';
							}
						?>
						</tbody>
						<tfoot>
							<tr>
								<th>ID</th>
								<th>Number</th>
								<th>Name</th>
								<th>MAC</th>
								<th>Latitude</th>
								<th>Longitude</th>
								<th>Country</th>
								<th>First Report</th>
								<th>Last Report</th>
								<th>IP</th>
								<th>Uptime</th>
								<th>Status</th>
							</tr>
						</tfoot>
					</table>
	    </div>
		
		<script src="js/jquery.js"></script>	
		<script src="js/bootstrap.min.js"></script>
		<script src="js/sha512.js"></script>
		<script src="js/form.js"></script>
		
		<script src="js/highcharts.js"></script>
		
		<script src="js/jquery.dataTables.min.js"></script>
		<script src="js/ColReorder.min.js"></script>
		
		<script type="text/javascript" charset="utf-8">
			$(document).ready( function () {
				var oTable = $('#grid').dataTable( {
					"sDom": 'R<"H"lfr>t<"F"ip>',
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				} );
				
				$('#online').text("<?php echo $online_count; ?>");
				$('#offline').text("<?php echo $offline_count; ?>");
			});
			
			$(function () { 
			    $('#chart_status').highcharts({
					chart: {
		                plotBackgroundColor: null,
		                plotBorderWidth: null,
		                plotShadow: false
		            },
		            title: {
		                text: 'Pineapples Status'
		            },
					tooltip: {
					    formatter: function () {
					                   return this.point.name + ': <b>' + Highcharts.numberFormat(this.percentage, 1) + '%</b>';
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
		                            return this.point.name + ': <b>' + Highcharts.numberFormat(this.percentage, 1) + '%</b>';
		                        }
		                    }
		                }
		            },
					series: [{
		                type: 'pie',
		                name: 'Pineapples Status',
		                data: [
		                    ['Online', <?php echo $online_count; ?>],
		                    ['Offline', <?php echo $offline_count; ?>]
		                ]
					}]
			    });
			});
			
			$(function () { 
			    $('#chart_location').highcharts({
					chart: {
		                plotBackgroundColor: null,
		                plotBorderWidth: null,
		                plotShadow: false
		            },
		            title: {
		                text: 'Pineapples Location'
		            },
					tooltip: {
					    formatter: function () {
					                   return this.point.name + ': <b>' + Highcharts.numberFormat(this.percentage, 1) + '%</b>';
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
		                            return this.point.name + ': <b>' + Highcharts.numberFormat(this.percentage, 1) + '%</b>';
		                        }
		                    }
		                }
		            },
					series: [{
		                type: 'pie',
		                name: 'Pineapples Location',
		                data: [
						<?php
						
							$i=1; $all=count(array_count_values($countries));
							foreach(array_count_values($countries) as $key => $value)
							{
								echo "['".$key."', ".$value."]";
								if($i != $all) echo ",";
								
								$i++;
							}
						?>
		                ]
					}]
			    });
			});
			
		</script>
		
</body>
</html>

