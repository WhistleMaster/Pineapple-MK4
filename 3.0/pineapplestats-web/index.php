<?php 
	include('includes/db_connect.php'); 
	include('includes/functions.php'); 
	
	sec_session_start();
	
	if(!$conf['installed'])
	{
		header('Location: ./install.php');
	}
	
	if(login_check($mysqli) == true)
	{
		header('Location: ./pineapple.php');
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
<body OnLoad="$('#email').focus();">
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
				if(isset($_GET['error'])) 
					echo '<div class="alert alert-error">Wrong login</div>'; 
			?>
		  
			<div class="row">				
				<div class="span4 offset4">
					<form id="login_form" class="form-signin" action="login.php" method="POST" onkeypress="if(event.keyCode == 13) formhash(document.forms['login_form'], document.forms['login_form'].password);">
						<h2 class="form-signin-heading">Please sign in</h2>
						<input type="text" id="email" name="email" class="input-block-level" placeholder="Email">
						<input id="password" type="password" name="password" class="input-block-level" placeholder="Password">
						<button class="btn btn-large" name="login" type="button" onclick="formhash(this.form, this.form.password);">Sign in</button>
					</form>
				</div>
			</div>
	    </div>
		
		<script src="js/jquery.js"></script>		
		<script src="js/bootstrap.min.js"></script>
		<script src="js/sha512.js"></script>
		<script src="js/form.js"></script>
		
</body>
</html>

