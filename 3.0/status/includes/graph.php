<?php

require("/pineapple/components/infusions/status/handler.php");
require("/pineapple/components/infusions/status/functions.php");

global $directory;

require($directory."includes/vars.php");

?>
<script type='text/javascript' src='/components/infusions/status/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/status/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/status/includes/css/infusion.css')</style>
	
<script type="text/javascript">
	$(document).ready(function(){ 
		$("#tabs ul").idTabs();
	});
</script>
	
<?php

if(isset($_GET['w']) && $_GET['w'] == 'cpu')
{
	echo '<fieldset class="status">';

	echo '<legend class="status">CPU Monitoring</legend>';

	echo '<iframe src="/components/infusions/status/includes/svg/graph_cpu.svg" width="100%" height="275" frameborder="0" type="image/svg+xml">';
	echo "</iframe>";
	
	echo '</fieldset>';
}
else
{
	echo '<fieldset class="status">';

	echo '<legend class="status">Bandwidth Monitoring</legend>';

	echo '<div id="tabs" class="tab"><ul>';
	for($i=0;$i<count($interfaces);$i++)
	{
		if($i == 0) $class = "selected"; else $class = "";
		$tmp_int = str_replace(".", "_", $interfaces[$i]);
	
		echo '<li><a class="'.$class.'" href="#'.$tmp_int.'">'.$interfaces[$i].'</a></li>';
	}
	echo '</ul>';

	for($i=0;$i<count($interfaces);$i++)
	{
		$tmp_int = str_replace(".", "_", $interfaces[$i]);
		echo '<div id="'.$tmp_int.'">';
	
		echo '<iframe src="/components/infusions/status/includes/svg/graph_if.svg?'.$interfaces[$i].'" width="100%" height="275" frameborder="0" type="image/svg+xml">';
		echo "</iframe>";
	
		echo '</div>';
	}

	echo '</div>';
	echo '</fieldset>';
}

?>