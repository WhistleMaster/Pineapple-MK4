<?php

global $directory, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/wifimanager/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/wifimanager/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ wifimanager_init_small(); });
</script>

<?php

echo '[<a id="refresh" href="javascript:wifimanager_refresh_tile();">Refresh</a>]&nbsp;<span id="wifimanager_small" class="refresh_text"></span><br/><br/>';
echo '<div id="wifimanager_interfaces_tile"></div>';

?>

<div id="wifimanager" class="loading"></div>

