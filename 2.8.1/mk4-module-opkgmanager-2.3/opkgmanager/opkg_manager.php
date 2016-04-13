<?php

require("opkg_manager_vars.php");

if(!file_exists("/var/opkg-lists/snapshots")) shell_exec ("opkg update");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<link href="css/table_jui.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/jquery-ui-1.8.4.custom.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/ColReorder.css" media="screen" rel="stylesheet" type="text/css" />

<script type="text/javascript" charset="utf-8" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf-8" src="js/ColReorder.js"></script>

<script type="text/javascript" src="js/opkg_manager.js"></script>
<link rel="stylesheet" type="text/css" href="css/opkg_manager.css" />
<link rel="stylesheet" type="text/css" href="css/firmware.css" />

<link rel="icon" href="/favicon.ico" type="image/x-icon"> 
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">

<script type="text/javascript" charset="utf-8">
	$(document).ready( function () { init(); });	
</script>

<?php if(file_exists("/pineapple/includes/navbar.php")) require('/pineapple/includes/navbar.php'); ?>

<p id="version">v<?php echo $module_version; ?></p>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $module_name; ?> <span id="refresh_text"></span></div>
<div class=sidePanelContent>
Installed Packages <font color="lime"><?php echo exec("opkg list-installed | wc -l"); ?></font><br/>
Available Packages <font color="lime"><span id="pack"><?php echo exec("opkg list | wc -l"); ?></span></font> | <a id="update" href="javascript:update();"><strong>Update</strong></a><br/><br/>
Cached data <font color="lime"> <span id="cache">
<?
if(file_exists("/tmp/opkg_list_all_html"))
{
	$date_cache = date("M d Y", filemtime("/tmp/opkg_list_all_html"));
	echo 'yes ['.$date_cache.']';
}
else
{
	echo 'no';
}
?>
</span></font> | <a id="update_cache" href="javascript:update_cache();"><strong>Update Cache</strong></a>
</div>
</div>

[<a id="refresh" href="javascript:refresh();"><strong>Refresh</strong></a>]<br /><br />
<div id="content">Loading...</div>
<br />
<strong>Output</strong>
<br /><pre><div id="output">-</div></pre>

</pre>
</body>
</html>
