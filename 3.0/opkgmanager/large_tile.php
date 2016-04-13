<?php

global $directory, $rel_dir, $version, $name;

if(!file_exists("/var/opkg-lists/snapshots")) shell_exec ("opkg update");

?>

<script type='text/javascript' src='/components/infusions/opkgmanager/includes/js/jquery.dataTables.min.js'></script>
<script type='text/javascript' src='/components/infusions/opkgmanager/includes/js/ColReorder.js'></script>
<script type='text/javascript' src='/components/infusions/opkgmanager/includes/js/infusion.js'></script>

<style>@import url('/components/infusions/opkgmanager/includes/css/table_jui.css')</style>
<style>@import url('/components/infusions/opkgmanager/includes/css/jquery-ui-1.8.4.custom.css')</style>
<style>@import url('/components/infusions/opkgmanager/includes/css/ColReorder.css')</style>
<style>@import url('/components/infusions/opkgmanager/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ opkgmanager_init(); });
</script>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $name; ?> - v<?php echo $version; ?>&nbsp;<span id="opkgmanager" class="refresh_text"></span></div>
<div class=sidePanelContent>
Installed Packages <font color="lime"><?php echo exec("opkg list-installed | wc -l"); ?></font><br/>
Available Packages <font color="lime"><span id="pack"><?php echo exec("opkg list | wc -l"); ?></span></font> | <a id="update" href="javascript:opkgmanager_update();"><strong>Update</strong></a>
</div>
</div>

[<a id="refresh" href="javascript:opkgmanager_refresh();"><strong>Refresh</strong></a>]<br /><br />
<div id="opkgmanager_content">...</div>