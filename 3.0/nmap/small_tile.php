<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/nmap/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/nmap/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ nmap_init_small(); });
</script>

<?php

if($is_nmap_installed)
{
	if($target_run != "")
		echo '<input class="nmap" type="text" id="target_small" name="target_small" placeholder="Target" value="'.$target_run.'" size="30">&nbsp;';
	else
		echo '<input class="nmap" type="text" id="target_small" name="target_small" placeholder="Target" value="" size="30">&nbsp;';

	echo '<select class="nmap" id="profile_small" name="profile_small"><option>--</option>';
	foreach($profiles as $key => $value)
	{
		if($profile_run != "" && $profile_run == $key)
			echo '<option selected value="'.$value.'">'.$key.'</option>';
		else
			echo '<option value="'.$value.'">'.$key.'</option>';
	}
	echo '</select>&nbsp;';
	
	echo '<span id="nmap_control_small">';
	if($is_nmap_running)
	{
		echo '<a id="scan_small" href="javascript:nmap_scan_toggle_small(\'cancel\');"><font color="red"><strong>Cancel</strong></font></a>';
	}
	else
	{
		echo '<a id="scan_small" href="javascript:nmap_scan_toggle_small(\'scan\');"><font color="lime"><strong>Scan</strong></font></a>';
	}
	echo '</span><br/>';
	
	if($cmd_run != "")
		echo '<input class="nmap" type="text" id="nmap_command_small" name="nmap_command_small" value="'.$cmd_run.'" size="70"><br/><br/>';
	else
		echo '<input class="nmap" type="text" id="nmap_command_small" name="nmap_command_small" value="nmap " size="70"><br/><br/>';
		
	echo "<textarea readonly class='nmap' id='nmap_output_small' name='nmap_output_small'></textarea>";
}
else
{
	echo "nmap";
	echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font><br /><br />";
}

?>