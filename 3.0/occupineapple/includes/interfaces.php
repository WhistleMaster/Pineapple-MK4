<?php

require("/pineapple/components/infusions/occupineapple/handler.php");

global $directory;

require($directory."includes/vars.php");

if(isset($_GET['monitor']))
{
	echo '<select class="occupineapple" id="monitorInterfaces" name="monitorInterfaces">';
	foreach($monitorInterfaces as $value) { echo '<option value="'.$value.'">'.$value.'</option>'; }
	echo '</select>';
}

if(isset($_GET['interface']))
{
	echo '<select class="occupineapple" id="interfaces" name="interfaces">';
	foreach($interfaces as $value) { echo '<option value="'.$value.'">'.$value.'</option>'; }
	echo '</select>';
}

?>
