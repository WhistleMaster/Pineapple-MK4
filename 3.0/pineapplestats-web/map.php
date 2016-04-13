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
					  <li class="active"><a href="./map.php"><i class="icon-globe"></i>&nbsp;Map</a></li>
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
			
			<div class="row"><div class="span12"><div id="map_canvas"></div></div></div>
			<div class="row"><div class="span12">&nbsp;</div></div>
			<div class="row">
				<div class="span12">
					<div id="reportrange" class="pull-right">
					    <i class="icon-calendar icon-large"></i>
					    <span><?php echo date("F j, Y",strtotime($start_date))." - ".date("F j, Y",strtotime($end_date)); ?></span> <b class="caret"></b>
					</div>
					
					<button style="margin-bottom: 10px;" class="btn btn-info" type="button" onclick="javascript:resetFilters();"><i class="icon-refresh icon-white"></i> Reset Filter(s)</button>
				</div>
			</div>
		  
		    <div class="row"><div class="span12">
			<?php
			
				if(login_check($mysqli) == true)
				{					
					?>
					<table cellpadding="0" cellspacing="0" border="0" class="display" id="grid" width="100%">
						<thead>
							<tr>
								<th>Timestamp</th>
								<th>Pineapple Name</th>
								<th>Station SSID</th>
								<th>Station MAC</th>
								<th>Station Signal</th>
								<th>Station Signal Quality</th>
							</tr>
						</thead>
						<tbody>
		
						</tbody>
						<tfoot>
							<tr>
								<th>Timestamp</th>
								<th>Pineapple Name</th>
								<th>Station SSID</th>
								<th>Station MAC</th>
								<th>Station Signal</th>
								<th>Station Signal Quality</th>
							</tr>
						</tfoot>
					</table>
					
				<?php
				}
				else
				{
					header('Location: ./');
				}
			
			?>
			</div></div>
	    </div>
		
		<script src="js/jquery.js"></script>		
		<script src="js/bootstrap.min.js"></script>
		<script src="js/sha512.js"></script>
		<script src="js/form.js"></script>
		
		<script src="js/jquery.dataTables.min.js"></script>
		<script src="js/dataTables.fnReloadAjax.js"></script>
		<script src="js/dataTables.fnSetFilteringDelay.js"></script>
		<script src="js/ColReorder.min.js"></script>
		
        <script src="js/moment.min.js"></script>
        <script src="js/daterangepicker.js"></script>
		
		<script type="text/javascript" charset="utf-8">
			var oTable;
			
			$(document).ready( function () {
				oTable = $('#grid').dataTable( {
					"sDom": 'R<"H"lfr>t<"F"ip>',
					"bJQueryUI": true,
					"sPaginationType": "full_numbers",
					"bServerSide": true,
					"bProcessing": true,
					"sAjaxSource": "getdata.php?Date_Start=<?php echo $start_date; ?>&Date_End=<?php echo $end_date; ?>"
				} );
			
				oTable.fnSetFilteringDelay();
				
				$('#map_canvas').width("100%").height("350px").gmap({'zoom':2, 'callback': function() {
					var self = this;
			
					<?php 	
						$result = $mysqli->query("SELECT * FROM PineappleStats_Pineapples");
				
						while($row = $result->fetch_assoc())
						{
							echo "self.addMarker({'position': '".$row['Pineapple_Latitude'].",".$row['Pineapple_Longitude']."', 'bounds': false, 'icon': './img/pineapple.png'}).click(function() {";
							echo "oTable.fnReloadAjax('getdata.php?Pineapple_ID=".$row['Pineapple_ID']."&Date_Start=".$start_date."&Date_End=".$end_date."');";
							
							$data = "<strong>Pineapple</strong>: ".$row['Pineapple_Name']."</br>";
							
							echo "self.openInfoWindow({'content': '".$data."'}, this);";
							echo "});";
						}
					?>
				}});
			});
		
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
	                //oTable.fnReloadAjax('getdata.php?Date_Start='+start.format('YYYY-MM-DD')+'&Date_End='+end.format('YYYY-MM-DD')); 
	   			 	//$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
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
		</script>
		
		<script src="http://maps.google.com/maps/api/js?sensor=true"></script>
		<script src="js/jquery.ui.map.full.min.js"></script>
		
		<script src="js/bootstrap-datepicker.js"></script>
</body>
</html>

