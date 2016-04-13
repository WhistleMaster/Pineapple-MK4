<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/jammer/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/jammer/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ jammer_init_small(); });
</script>

<?php

echo '[<a id="refresh" href="javascript:jammer_refresh_tile();">Refresh</a>]&nbsp;<span id="jammer_small" class="refresh_text"></span><br/><br/>';

echo "WiFi Jammer ";

if ($is_jammer_running)
{
	echo "<span id=\"jammer_status_small\"><font color=\"lime\"><strong>enabled</strong></font></span>";
	echo " | <a id=\"jammer_link_small\" href=\"javascript:jammer_toggle_small('stop');\"><strong>Stop</strong></a>&nbsp;";
}
else 
{ 
	echo "<span id=\"jammer_status_small\"><font color=\"red\"><strong>disabled</strong></font></span>";
	echo " | <a id=\"jammer_link_small\" href=\"javascript:jammer_toggle_small('start');\"><strong>Start</strong></a>&nbsp;"; 
}

echo '<select class="jammer" id="jammer_interfaces_small" name="jammer_interfaces_small">';
foreach($interfaces as $value) 
{ 
	if($interface_conf == $value)
		echo '<option selected value="'.$value.'">'.$value.'</option>'; 
	else
		echo '<option value="'.$value.'">'.$value.'</option>'; 
}
echo '</select><br/><br/>';

echo "<textarea readonly class='jammer' id='jammer_output_small' name='jammer_output_small'></textarea>";

?>