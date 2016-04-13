<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/monitor/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/monitor/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/monitor/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ monitor_init_small(); });
</script>

<?php

echo '[<a id="refresh" href="javascript:monitor_refresh_tile();">Refresh</a>]&nbsp;<span id="monitor_small" class="refresh_text"></span><br/>';

if($is_vnstat_installed && $is_vnstati_installed)
{
	echo '<div id="monitor_content_small"></div>';
}
else
{
	echo "vnStat";
	echo "&nbsp;<span id=\"vnstat_status\"><font color=\"red\"><strong>not installed</strong></font></span><br />";
}

?>