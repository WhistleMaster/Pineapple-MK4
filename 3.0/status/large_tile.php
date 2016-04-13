<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/status/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/status/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/status/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ status_init(); });
</script>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $name; ?> - v<?php echo $version; ?>&nbsp;<span id="status" class="refresh_text"></span></div>
<div class=sidePanelContent id=sidePanelContent>
<br/>[<a id="refresh" href="javascript:status_refresh();">Refresh</a>] [<a id="refresh" href="javascript:status_graph('interfaces');">Bandwidth Graph</a>] [<a id="refresh" href="javascript:status_graph('cpu');">CPU Graph</a>]
</div>
</div>

<div id="status_content"></div>