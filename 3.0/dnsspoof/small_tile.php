<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/dnsspoof/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/dnsspoof/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ dnsspoof_init_small(); });
</script>

<?php

echo '[<a id="refresh" href="javascript:dnsspoof_refresh_tile();">Refresh</a>]&nbsp;<span id="dnsspoof_small" class="refresh_text"></span><br/><br/>';

if($is_dnsspoof_installed)
{
	if ($is_dnsspoof_running) 
	{
		echo "dnsspoof <span id=\"dnsspoof_status_small\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"dnsspoof_link_small\" href=\"javascript:dnsspoof_toggle_small('stop');\"><strong>Stop</strong></a><br /><br />";
	}
	else
	{ 
		echo "dnsspoof <span id=\"dnsspoof_status_small\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"dnsspoof_link_small\" href=\"javascript:dnsspoof_toggle_small('start');\"><strong>Start</strong></a><br /><br />"; 
	}
	
	echo "<textarea class='dnsspoof' readonly class='dnsspoof' id='dnsspoof_output_small' name='dnsspoof_output_small'></textarea>";
}
else
{
	echo "dnsspoof";
	echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font><br /><br />";
}

?>