<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/status/includes/js/infusion.js'></script>
<script type='text/javascript' src='/components/infusions/status/includes/js/jquery.idTabs.min.js'></script>
<style>@import url('/components/infusions/status/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ status_init_small(); });
</script>

<?php

echo '[<a id="refresh" href="javascript:status_refresh_tile();">Refresh</a>]&nbsp;<span id="status_small" class="refresh_text"></span><br/><br/>';
echo '<div id="status_content_small"></div>';

?>