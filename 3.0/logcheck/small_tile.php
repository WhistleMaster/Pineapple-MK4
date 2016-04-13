<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/logcheck/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/logcheck/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ logcheck_init_small(); });
</script>

<?php

echo '[<a id="refresh" href="javascript:logcheck_refresh_tile();">Refresh</a>]&nbsp;<span id="logcheck_small" class="refresh_text"></span><br/><br/>';

if($is_ssmtp_installed)
{
	if ($is_logcheck_running) 
	{
		echo "Logcheck <span id=\"logcheck_status_small\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"logcheck_link_small\" href=\"javascript:logcheck_toggle_small('stop');\"><strong>Stop</strong></a><br /><br />";
	}
	else 
	{ 
		echo "Logcheck <span id=\"logcheck_status_small\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"logcheck_link_small\" href=\"javascript:logcheck_toggle_small('start');\"><strong>Start</strong></a><br /><br />"; 
	}
	
	echo "<textarea readonly class='logcheck' id='logcheck_output_small' name='logcheck_output_small'></textarea>";
}
else
{
	echo "ssmtp";
	echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font><br /><br />";
}

?>
