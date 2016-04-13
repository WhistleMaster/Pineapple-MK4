<?php

global $directory, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/wifimanager/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/wifimanager/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/wifimanager/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ wifimanager_init(); });
</script>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $name; ?> - v<?php echo $version; ?>&nbsp;<span id="wifimanager" class="refresh_text"></span></div>
<div class=sidePanelContent id=sidePanelContent></div>
</div>

<div id="content"></div>