<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/tcpdump/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/tcpdump/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ tcpdump_init_small(); });
</script>

<?php

if($is_tcpdump_installed)
{
	echo '<select class="tcpdump" id="tcpdump_interface_small" name="tcpdump_interface_small"><option>--</option>';
	foreach($interfaces as $key => $value)
	{
		if($int_run != "" && $int_run == $key)
			echo '<option selected value="'.$value.'">'.$key.'</option>';
		else
			echo '<option value="'.$value.'">'.$key.'</option>';
	}
	echo '</select>&nbsp;';
	
	echo '<span id="control_small">';
	if($is_tcpdump_running)
	{
		echo '<a id="dump_small" href="javascript:tcpdump_dump_toggle_small(\'stop\');"><font color="red"><strong>Stop</strong></font></a><br />';
	}
	else
	{
		echo '<a id="dump_small" href="javascript:tcpdump_dump_toggle_small(\'capture\');"><font color="lime"><strong>Capture</strong></font></a><br />';
	}
	echo '</span>';
	
	if($cmd_run != "")
		echo '<input class="tcpdump" type="text" id="tcpdump_command_small" name="tcpdump_command_small" value="'.$cmd_run.'" size="70"><br /><br />';
	else
		echo '<input class="tcpdump" type="text" id="tcpdump_command_small" name="tcpdump_command_small" value="tcpdump " size="70"><br /><br />';
	
	echo "<textarea readonly class='tcpdump' id='tcpdump_output_small' name='tcpdump_output_small'></textarea>";
}
else
{
	echo "tcpdump";
	echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font><br /><br />";
}

?>