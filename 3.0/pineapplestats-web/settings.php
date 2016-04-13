<?php 
	include('includes/db_connect.php'); 
	include('includes/functions.php'); 
	
	sec_session_start();
	
	if(login_check($mysqli) != true)
	{
		header('Location: ./');
	}
	
	$sucess=0;
	if(isset($_POST['username'], $_POST['email']) && $_POST['username'] != "" && $_POST['email'] != "")
	{
	    if ($stmt = $mysqli->prepare("SELECT Login_Password, Login_Salt FROM PineappleStats_Login WHERE Login_ID = ? LIMIT 1")) 
	    { 
	       $stmt->bind_param('s', $_SESSION['user_id']);
	       $stmt->execute(); 
	       $stmt->store_result();
	       $stmt->bind_result($db_password, $salt); 
	       $stmt->fetch();
	   }
	   
	   if(isset($_POST['p']))
	   {
		   $password = $_POST['p'];
		   $password = hash('sha512', $password.$salt);
	   }
	   else
	   {
		   $password = $db_password;
	   }
		
		$username = $_POST['username'];
		$email = $_POST['email'];
 
		if ($insert_stmt = $mysqli->prepare("UPDATE PineappleStats_Login SET Login_Username = ?, Login_Email = ?, Login_Password = ? WHERE Login_ID = ?")) {    
		   $insert_stmt->bind_param('ssss', $username, $email, $password, $_SESSION['user_id']);
		   $insert_stmt->execute();
		}
		
		$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
		$_SESSION['username'] = $username;
		
	   $sucess=1;
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
					  <li><a href="./tools.php"><i class="icon-wrench"></i>&nbsp;Tools</a></li>
				  </ul>
				  <ul class="nav pull-right">
					  <li class="active dropdown">
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
					echo '<div class="alert alert-success"><strong>account updated!</strong></div>'; 
			?>
		
			<?php
						
				if(login_check($mysqli) == true)
				{
				    if ($stmt = $mysqli->prepare("SELECT Login_Email, Login_Username FROM PineappleStats_Login WHERE Login_ID = ? LIMIT 1")) 
				    { 
				       $stmt->bind_param('s', $_SESSION['user_id']);
				       $stmt->execute(); 
				       $stmt->store_result();
				       $stmt->bind_result($email, $username); 
				       $stmt->fetch();
				   }
			?>
				<div class="row">
					
					<div class="span4 offset4">
			  		  	<form class="form-signin" action="" method="POST">
							<h4 class="form-signin-heading">Account Information</h4>
							<input type="text" name="username" value="<?php echo $username; ?>" class="input-block-level" placeholder="Username">
							<input type="text" name="email" value="<?php echo $email; ?>" class="input-block-level" placeholder="Email">
							<input id="password" type="password" name="password" class="input-block-level" placeholder="Password">
							<input id="password_conf" type="password" name="password_conf" class="input-block-level" placeholder="Password Confirmation">
							<button class="btn btn-small" name="login" type="button" onclick="updateformhash(this.form, this.form.password, this.form.password_conf);">Update</button>
						</form>
					</div>
					
				</div>
					
			<?php
			
				}
				else
				{
					header('Location: ./');
				}
			
			?>
	    </div>
		
		<script src="js/jquery.js"></script>		
		<script src="js/bootstrap.min.js"></script>
		<script src="js/sha512.js"></script>
		<script src="js/form.js"></script>
				
</body>
</html>

