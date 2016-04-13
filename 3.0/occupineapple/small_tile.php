<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/occupineapple/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/occupineapple/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ occupineapple_init_small(); });
</script>

<?php

echo '[<a id="refresh" href="javascript:occupineapple_refresh_tile();">Refresh</a>]&nbsp;<span id="occupineapple_small" class="refresh_text"></span><br/><br/>';

if($is_mdk3_installed)
{
	if ($is_mdk3_running)
	{
		echo "Occupineapple <span id=\"mdk3_status_small\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"mdk3_link_small\" href=\"javascript:occupineapple_mdk3_toggle_small('stop');\"><strong>Stop</strong></a> ";
	}
	else
	{ 
		echo "Occupineapple <span id=\"mdk3_status_small\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"mdk3_link_small\" href=\"javascript:occupineapple_mdk3_toggle_small('start');\"><strong>Start</strong></a> "; 
	}
	
	if($is_mdk3_running)
		echo '<select class="occupineapple" disabled="disabled" id="list_small" name="list_small">';
	else
		echo '<select class="occupineapple" id="list_small" name="list_small">';
	
	echo '<option>--</option>';
	$lists_list = array_reverse(glob($directory."includes/lists/*"));

	for($i=0;$i<count($lists_list);$i++)
	{
		if($occupineapple_run == basename($lists_list[$i]))
			echo '<option selected value="'.basename($lists_list[$i]).'">'.basename($lists_list[$i]).'</option>';
		else
			echo '<option value="'.basename($lists_list[$i]).'">'.basename($lists_list[$i]).'</option>';
	}
	echo '</select> <br/><br/>';
	
	echo "<textarea readonly class='occupineapple' id='occupineapple_output_small' name='occupineapple_output_small'></textarea>";
}
else
{
	echo "mdk3";
	echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font><br /><br />";
}

?>