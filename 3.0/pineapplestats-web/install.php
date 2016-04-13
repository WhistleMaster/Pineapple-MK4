<?php 

	$serverpath = str_replace('\\','/',dirname($_SERVER['SCRIPT_FILENAME']));
	define("CONFIGFILE",$serverpath.'/includes/pineapplestats.cfg');

	eval(file_get_contents(CONFIGFILE));

	include('includes/functions.php'); 
	
	if($conf['installed'])
	{
		header('Location: ./');
	}
	
	$sucess=0;
	if(isset($_POST['mysql_user'], $_POST['mysql_pass'], $_POST['mysql_host'], $_POST['mysql_database']))
	{
		global $cfg;
		$cfg = @file_get_contents(CONFIGFILE);
		
		updateConfigItem('mysql_user', sanitize($_POST['mysql_user']));
		updateConfigItem('mysql_pass', sanitize($_POST['mysql_pass']));
		updateConfigItem('mysql_host', sanitize($_POST['mysql_host']));
		updateConfigItem('mysql_database', sanitize($_POST['mysql_database']));
		
		updateConfigItem('installed', 1);
		
		if(is_writeable(CONFIGFILE))
		{
			if(updateConfigFile($cfg))
			{
				if(isset($_POST['username'], $_POST['email'], $_POST['p']) && $_POST['username'] != "" && $_POST['email'] != "" && $_POST['p'] != "")
				{
					eval(file_get_contents(CONFIGFILE));
					include('includes/db_connect.php');
					
					$password = $_POST['p'];
					$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
					$password = hash('sha512', $password.$random_salt);

					$username = $_POST['username'];
					$email = $_POST['email'];

					if ($insert_stmt = $mysqli->prepare("INSERT INTO PineappleStats_Login (Login_Username, Login_Email, Login_Password, Login_Salt) VALUES (?, ?, ?, ?)")) 
					{    
					   $insert_stmt->bind_param('ssss', $username, $email, $password, $random_salt); 
					   $insert_stmt->execute();
					}

					$sucess = 1;
				}
			}
		}
		else
		{
			$sucess = 2;
		}
		
		unset($cfg);
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
	          </div><!--/.nav-collapse -->
	        </div>
	      </div>
	    </div>

	    <div class="container">
			
			<?php 
				if($sucess == 1) 
					echo '<div class="alert alert-success"><strong>information updated!</strong>&nbsp;<a href="./"><button class="btn btn-mini" type="button">Start</button></a></div>';
				if($sucess == 2)
					echo '<div class="alert alert-error"><strong>pineapplestats.cfg is not writable. Make sure permissions are 666.</strong></div>';
			?>
			
			<form class="form-signin" action="" method="POST">
			<div class="row">
				<div class="span4 offset2">
						<h4 class="form-signin-heading">DB Information</h4>
						<input type="text" name="mysql_user" value="" class="input-block-level" placeholder="mysql user">
						<input type="password" name="mysql_pass" value="" class="input-block-level" placeholder="mysql pass">
						<input type="text" name="mysql_host" value="" class="input-block-level" placeholder="mysql host">
						<input type="text" name="mysql_database" value="" class="input-block-level" placeholder="mysql database">
				</div>

				<div class="span4">
						<h4 class="form-signin-heading">Account Information</h4>
						<input type="text" name="username" value="" class="input-block-level" placeholder="Username">
						<input type="text" name="email" value="" class="input-block-level" placeholder="Email">
						<input id="password" type="password" name="password" class="input-block-level" placeholder="Password">
						<input id="password_conf" type="password" name="password_conf" class="input-block-level" placeholder="Password Confirmation">
				</div>
			</div>
			<div class="row">
				<div class="span4 offset2">
					<button class="btn btn-small" name="login" type="button" onclick="updateformhash(this.form, this.form.password, this.form.password_conf);">Update</button>
				</div>
			</div>
			</form>
			
	    </div>
		
		<script src="js/jquery.js"></script>		
		<script src="js/bootstrap.min.js"></script>
		<script src="js/sha512.js"></script>
		<script src="js/form.js"></script>
		
</body>
</html>

