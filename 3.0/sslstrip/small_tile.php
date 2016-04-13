<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/sslstrip/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/sslstrip/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ sslstrip_init_small(); });
</script>

<?php

echo '[<a id="refresh" href="javascript:sslstrip_refresh_tile();">Refresh</a>]&nbsp;<span id="sslstrip_small" class="refresh_text"></span><br/><br/>';

if($is_sslstrip_installed)
{
	if ($is_sslstrip_running)
	{
		echo "sslstrip <span id=\"sslstrip_status_small\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"sslstrip_link_small\" href=\"javascript:sslstrip_toggle_small('stop');\"><strong>Stop</strong></a> ";
		if($is_verbose)
			echo '<input class="sslstrip" type="checkbox" checked="checked" disabled="disabled" id="verbose_small" name="verbose_small" value="verbose" /> Verbose<br /><br />';
		else
			echo '<input class="sslstrip" type="checkbox" disabled="disabled" id="verbose_small" name="verbose_small" value="verbose" /> Verbose<br /><br />';
	}
	else
	{ 
		echo "sslstrip <span id=\"sslstrip_status_small\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"sslstrip_link_small\" href=\"javascript:sslstrip_toggle_small('start');\"><strong>Start</strong></a> "; 
		if($is_verbose)
			echo '<input class="sslstrip" type="checkbox" checked="checked" id="verbose_small" name="verbose_small" value="verbose" /> Verbose<br /><br />';
		else
			echo '<input class="sslstrip" type="checkbox" id="verbose_small" name="verbose_small" value="verbose_small" /> Verbose<br /><br />';
	}
	
	echo "<textarea readonly class='sslstrip' id='sslstrip_output_small' name='sslstrip_output_small'></textarea>";
}
else
{
	echo "sslstrip";
	echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font><br /><br />";
}

?>