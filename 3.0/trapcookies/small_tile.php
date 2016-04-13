<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/trapcookies/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/trapcookies/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ trapcookies_init_small(); });
</script>

<?php

echo '[<a id="refresh" href="javascript:trapcookies_refresh_tile();">Refresh</a>]&nbsp;<span id="trapcookies_small" class="refresh_text"></span><br/><br/>';

if($is_ngrep_installed)
{
	if ($is_ngrep_running) 
	{
		echo "trapcookies <span id=\"trapcookies_status_small\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"trapcookies_link_small\" href=\"javascript:trapcookies_toggle_small('stop');\"><strong>Stop</strong></a><br /><br />";
	}
	else
	{ 
		echo "trapcookies <span id=\"trapcookies_status_small\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"trapcookies_link_small\" href=\"javascript:trapcookies_toggle_small('start');\"><strong>Start</strong></a><br /><br />"; 
	}
	
	echo "<textarea class='trapcookies' readonly class='trapcookies' id='trapcookies_output_small' name='trapcookies_output_small'></textarea>";
}
else
{
	echo "ngrep";
	echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font><br /><br />";
}

?>