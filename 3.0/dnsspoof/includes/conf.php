<?php

require("/pineapple/components/infusions/dnsspoof/handler.php");

global $directory, $rel_dir;

require($directory."includes/vars.php");

if (isset($_POST['set_conf']))
{	
	if($_POST['set_conf'] == "hosts")
	{
		$filename = $hosts_path;
		$newdata = $_POST['newdata'];

		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
		
		echo '<font color="lime"><strong>saved</strong></font>';
	}
	
	if($_POST['set_conf'] == "redirect")
	{
		$filename = $redirect_path;
		$newdata = $_POST['newdata'];

		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
		
		echo '<font color="lime"><strong>saved</strong></font>';
	}
}

?>