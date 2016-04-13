<?php

require("button_vars.php");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>

<script type="text/javascript" src="js/button.js"></script>
<link rel="stylesheet" type="text/css" href="css/button.css" />
<link rel="stylesheet" type="text/css" href="css/firmware.css" />

<link rel="icon" href="/favicon.ico" type="image/x-icon"> 
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">
	
<script type="text/javascript" charset="utf-8">
	$(document).ready( function () { init(); });	
</script>

<?php if(file_exists("/pineapple/includes/navbar.php")) require('/pineapple/includes/navbar.php'); ?>

<pre><p id="version">v<?php echo $module_version; ?></p></pre>
<?php

if(isset($_POST['_wpsx']))
{	
	for($i=0;$i<4;$i++)
	{
		$newdata = $_POST['newdata'.($i+1)];
		if ($newdata != "")
		{ 
			$filename = $module_path."/scripts/wpsScript".($i+1).".sh";
			
			$newdata = ereg_replace(13,  "", $newdata);
		 	$fw = fopen($filename, 'w') or die('Could not open file!');
		 	$fb = fwrite($fw,stripslashes($newdata)) or die('Could not write to file');
		 	fclose($fw);
		}
	}
	
	$newdata = $_POST['newdata5'];
	$filename = "/pineapple/config/wpsScript.sh";
			
	$newdata = ereg_replace(13,  "", $newdata);
 	$fw = fopen($filename, 'w') or die('Could not open file!');
 	$fb = fwrite($fw,stripslashes($newdata)) or die('Could not write to file');
 	fclose($fw);

	while(exec("uci get system.@button[1]") == "button")
	{
		exec("uci delete system.@button[1]");
	}
	exec("uci commit system");
	
	$_wpsx = $_POST['_wpsx'];
	
	for($i=0;$i<4;$i++)
	{
		exec("uci add system button");
		exec("uci set system.@button[".($i+1)."].button=reset");
		exec("uci set system.@button[".($i+1)."].action=released");
	
		if($_wpsx[$i] == 0) $handler = "logger No action";
		else if($_wpsx[$i] == 1) $handler = "php ".$module_path."toggle_services.php -karma";
		else if($_wpsx[$i] == 2) $handler = "php ".$module_path."toggle_services.php -dnsspoof";
		else if($_wpsx[$i] == 3) $handler = "php ".$module_path."toggle_services.php -snarf";
		else if($_wpsx[$i] == 4) $handler = "php ".$module_path."toggle_services.php -s";
		else if($_wpsx[$i] == 5) $handler = "reboot";
		else if($_wpsx[$i] == 6) $handler = "sh ".$module_path."/scripts/wpsScript1.sh";
		else if($_wpsx[$i] == 7) $handler = "sh ".$module_path."/scripts/wpsScript2.sh";
		else if($_wpsx[$i] == 8) $handler = "sh ".$module_path."/scripts/wpsScript3.sh";
		else if($_wpsx[$i] == 9) $handler = "sh ".$module_path."/scripts/wpsScript4.sh";
		else if($_wpsx[$i] == 10) $handler = "sh /pineapple/config/wpsScript.sh";

		exec("uci set system.@button[".($i+1)."].handler='".$handler."'");
		exec("uci set system.@button[".($i+1)."].min=".$_wpsx_min[$i]."");
		exec("uci set system.@button[".($i+1)."].max=".$_wpsx_max[$i]."");
		exec("uci commit system");
	}

	exec("uci commit system");
	
	echo "<font color=lime>Updated</font><br/>";
}

?>
<?php
echo "<form action='$_SERVER[php_self]' id='myform' name='myform' method='post' >";

for($i=0;$i<4;$i++)
{
	$status = exec("uci get system.@button[".($i+1)."].handler");
	
	if($status == "logger No action") $_wpsx[$i] = 0;
	else if($status == "php ".$module_path."toggle_services.php -karma") $_wpsx[$i] = 1;
	else if($status == "php ".$module_path."toggle_services.php -dnsspoof") $_wpsx[$i] = 2;
	else if($status == "php ".$module_path."toggle_services.php -snarf") $_wpsx[$i] = 3;
	else if($status == "php ".$module_path."toggle_services.php -s") $_wpsx[$i] = 4;
	else if($status == "reboot") $_wpsx[$i] = 5;
	else if($status == "sh ".$module_path."/scripts/wpsScript1.sh") $_wpsx[$i] = 6;
	else if($status == "sh ".$module_path."/scripts/wpsScript2.sh") $_wpsx[$i] = 7;
	else if($status == "sh ".$module_path."/scripts/wpsScript3.sh") $_wpsx[$i] = 8;
	else if($status == "sh ".$module_path."/scripts/wpsScript4.sh") $_wpsx[$i] = 9;
	else if($status == "sh /pineapple/config/wpsScript.sh") $_wpsx[$i] = 10;
}

?>

<strong>WPS Button Configuration</strong> [<a id="save" href="javascript:$('#myform').submit();">Save</a>] <span id="refresh_text"></span>
<div id="tabs" class="tab">
	<ul>
		<li><a id="Actions_link" class="selected" href="#Actions">Actions</a></li>
		<li><a id="Custom_link" href="#Custom">Custom Script</a></li>
	</ul>

<div id="Actions">
	<table class="fields">
	<tbody>
		<tr><td colspan="2" class="title indent1">When WPS button pushed for...</td></tr>
		<tr><td colspan="2" class="title indent1">&nbsp;</td></tr>
		<?php

			for($i=0;$i<sizeof($_wpsx_time);$i++)
			{
				echo '<tr><td class="title indent2"><label for="_wpsx_b'.$i.'">'.$_wpsx_time[$i].'</label></td>'."\n";
				echo '<td class="module_content">'."\n";
				echo '<select name="_wpsx[]" id="_wpsx_b'.$i.'">'."\n";

				for($j=0;$j<sizeof($_wpsx_values);$j++)
				{
					if($_wpsx[$i] == $j)
						echo '<option value="'.$j.'" selected>'.$_wpsx_values[$j].'</option>'."\n";
					else
						echo '<option value="'.$j.'">'.$_wpsx_values[$j].'</option>'."\n";				
				}

				echo '</select>'."\n";
				
				echo '</td></tr>'."\n";
			}

		?>
	</tbody>
	</table>
</div>

<?php

$filename = $module_path."/scripts/wpsScript1.sh";
$fh = fopen($filename, "r") or die("Could not open file!");
$data1 = fread($fh, filesize($filename)) or die("Could not read file!");
fclose($fh);

$filename = $module_path."/scripts/wpsScript2.sh";
$fh = fopen($filename, "r") or die("Could not open file!");
$data2 = fread($fh, filesize($filename)) or die("Could not read file!");
fclose($fh);

$filename = $module_path."/scripts/wpsScript3.sh";
$fh = fopen($filename, "r") or die("Could not open file!");
$data3 = fread($fh, filesize($filename)) or die("Could not read file!");
fclose($fh);

$filename = $module_path."/scripts/wpsScript4.sh";
$fh = fopen($filename, "r") or die("Could not open file!");
$data4 = fread($fh, filesize($filename)) or die("Could not read file!");
fclose($fh);

$filename = "/pineapple/config/wpsScript.sh";
$fh = fopen($filename, "r") or die("Could not open file!");
$data5 = fread($fh, filesize($filename)) or die("Could not read file!");
fclose($fh);

?>

<div id="Custom">
	<table class="fields">
	<tbody>
		<tr>
			<td class="title indent2"><label for="_wpsx_script">Default Pineapple Script</label></td>
			<td class="module_content"><textarea name="newdata5" id="_wpsx_script4"><?php echo $data5; ?></textarea></td>
		</tr>
		<tr>
			<td class="title indent2"><label for="_wpsx_script">Custom Script 1</label></td>
			<td class="module_content"><textarea name="newdata1" id="_wpsx_script1"><?php echo $data1; ?></textarea></td>
		</tr>
		<tr>
			<td class="title indent2"><label for="_wpsx_script">Custom Script 2</label></td>
			<td class="module_content"><textarea name="newdata2" id="_wpsx_script2"><?php echo $data2; ?></textarea></td>
		</tr>
		<tr>
			<td class="title indent2"><label for="_wpsx_script">Custom Script 3</label></td>
			<td class="module_content"><textarea name="newdata3" id="_wpsx_script3"><?php echo $data3; ?></textarea></td>
		</tr>
		<tr>
			<td class="title indent2"><label for="_wpsx_script">Custom Script 4</label></td>
			<td class="module_content"><textarea name="newdata4" id="_wpsx_script4"><?php echo $data4; ?></textarea></td>
		</tr>
	</tbody>
	</table>
</div>

</div>
</form>

</body>
</html>
