<?php

require("/pineapple/components/infusions/status/handler.php");
require("/pineapple/components/infusions/status/functions.php");

global $directory;

require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/status/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/status/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/status/includes/css/infusion.css')</style>
	
<?php

if(isset($_GET['cmd']))
{
	$cmd = $_GET['cmd'];
	
	echo '<fieldset class="status">';

	echo '<legend class="status">Execute: '.$cmd.'</legend>';

	exec ($cmd, $output);
	foreach($output as $outputline) {
	echo ("$outputline<br/>");}
	
	echo '</fieldset>';
}

?>

</body>
</html>