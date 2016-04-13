<?php

require("/pineapple/components/infusions/occupineapple/handler.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_GET['list_list']))
{
	$lists_list = array_reverse(glob($directory."includes/lists/*"));
	echo '<option>--</option>';
	for($i=0;$i<count($lists_list);$i++)
	{
		echo '<option value="'.basename($lists_list[$i]).'">'.basename($lists_list[$i]).'</option>';
	}
}

if (isset($_GET['show_list']))
{
	if (isset($_GET['which']))
	{
		$file = $directory."includes/lists/".$_GET['which'];
		echo file_get_contents($file);
	}
}

if (isset($_GET['delete_list']))
{
	if (isset($_GET['which']))
	{
		exec("rm -rf ".$directory."includes/lists/".$_GET['which']."*");
		
		echo '<font color="lime"><strong>done</strong></font>';
	}
}

if (isset($_POST['new_list']))
{
	if (isset($_POST['which']))
	{
		$filename = $directory."includes/lists/".$_POST['which'];
		
		if(!file_exists($filename))
		{
			$newdata = $_POST['newdata'];
			$newdata = ereg_replace(13,  "", $newdata);
			$fw = fopen($filename, 'w+');
			$fb = fwrite($fw,stripslashes($newdata));
			fclose($fw);
			
			echo '<font color="lime"><strong>done</strong></font>';
		}
	}
}

if (isset($_POST['save_list']))
{
	if (isset($_POST['which']))
	{
		$filename = $directory."includes/lists/".$_POST['which'];

		$newdata = $_POST['newdata'];
		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
		
		echo '<font color="lime"><strong>saved</strong></font>';
	}
}

?>