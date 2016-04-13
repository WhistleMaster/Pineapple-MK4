<?php 
	include('includes/db_connect.php'); 
	include('includes/functions.php'); 
	
	sec_session_start();
	
	if(login_check($mysqli) != true)
	{
		header('Location: ./');
	}
	
	$sucess=0;
	if(isset($_POST['token']) && $_POST['token'] != "")
	{
	   $token_exists = $mysqli->query("SELECT Token_ID FROM PineappleStats_Tokens WHERE `PineappleStats_Tokens`.`Token_Number` = '".$_POST['token']."' LIMIT 1");
	   
	   if($token_exists->num_rows == 0)
	   {
		   $mysqli->query("INSERT INTO `PineappleStats_Tokens` (`Token_ID`, `Token_Number`) VALUES ('', '".$_POST['token']."')");
		   $sucess=1;
	   }
	   else
	   {
		   $sucess=3;
	   }
	}
	else if(isset($_POST['token_id']))
	{
	   $mysqli->query("DELETE FROM `PineappleStats_Tokens` WHERE `PineappleStats_Tokens`.`Token_ID` = '".$_POST['token_id']."'");
	   $sucess=2;
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
					  <li><a href="./uptime.php"><i class="icon-hdd"></i>&nbsp;Uptime</a></li>
					  <li class="active"><a href="./tools.php"><i class="icon-wrench"></i>&nbsp;Tools</a></li>
				  </ul>
				  <ul class="nav pull-right">
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
			
			<?php 
				if($sucess == 1) 
					echo '<div class="alert alert-success"><strong>Token added!</strong></div>'; 
				else if($sucess == 2) 
					echo '<div class="alert alert-success"><strong>Token deleted!</strong></div>';
				else if($sucess == 3) 
					echo '<div class="alert alert-error"><strong>Token already exists!</strong></div>';
			?>

			<div class="row">
			
				<div class="span4">
		  		  	<form class="form-signin" action="" method="POST">
						<h4 class="form-signin-heading">Add Token</h4>
						<input type="text" name="token" class="input-block-level" placeholder="Token">
						<button class="btn btn-small" name="submit" type="submit">Add</button>
					</form>
				</div>
	
				<div class="span7 form-signin">
					<h4>Tokens List</h4>
				<?php
	
					$result = $mysqli->query("SELECT Token_ID, Token_Number FROM PineappleStats_Tokens");
	
					?>
					<table cellpadding="0" cellspacing="0" border="0" class="display" id="grid" width="100%">
						<thead>
							<tr>
								<th>Token Number</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>		
						<?php 	
							while($row = $result->fetch_assoc())
							{								
								echo '<tr>';
				
							    echo '<td>'.$row['Token_Number'].'</td>';
							    echo '<td style="text-align: center;"><form style="margin: 0;" action="" method="POST"><input type="hidden" name="token_id" value="'.$row['Token_ID'].'"><button class="btn btn-small" name="submit" type="submit">Delete</button></form></a></td>';
		
								echo '</tr>';
							}
						?>
						</tbody>
						<tfoot>
							<tr>
								<th>Token Number</th>
								<th>Actions</th>
							</tr>
						</tfoot>
					</table>
				</div>
			
			</div>
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
			});
		</script>
		
</body>
</html>

