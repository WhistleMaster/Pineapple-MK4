<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/occupineapple/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/occupineapple/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/occupineapple/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ occupineapple_init(); });
</script>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $name; ?> - v<?php echo $version; ?>&nbsp;<span id="occupineapple" class="refresh_text"></span></div>
<div class=sidePanelContent>
<?php
if($is_mdk3_installed)
{
	echo "WLAN interface ";
	echo "<span id=\"interfaces_l\">";
	echo '<select class="occupineapple" id="interfaces" name="interfaces">';
	foreach($interfaces as $value) 
	{ 
		if($interface_conf == $value)
			echo '<option selected value="'.$value.'">'.$value.'</option>'; 
		else
			echo '<option value="'.$value.'">'.$value.'</option>'; 
	}
	echo '</select>';
	echo "</span>";
	echo "&nbsp;| <a id=\"interface\" href=\"javascript:occupineapple_interface_toggle('start');\"><strong>Start</strong></a> - <a id=\"interface\" href=\"javascript:occupineapple_interface_toggle('stop');\"><strong>Stop</strong></a> [<a id=\"auto_interface\" href=\"javascript:occupineapple_auto_toggle();\"><strong>Auto</strong></a>] | <a id=\"monitorInterface\" href=\"javascript:occupineapple_monitor_toggle('start');\"><strong>Start Monitor</strong></a><br />";

	echo "Monitor interface ";
	echo "<span id=\"monitorInterface_l\">";
	echo '<select class="occupineapple" id="monitorInterfaces" name="monitorInterfaces">';
	foreach($monitorInterfaces as $value) 
	{ 
		if($monitor_conf == $value)
			echo '<option selected value="'.$value.'">'.$value.'</option>'; 
		else
			echo '<option value="'.$value.'">'.$value.'</option>'; 
	}
	echo '</select>';
	echo "</span>";
	echo "&nbsp;|  <a id=\"monitorInterface\" href=\"javascript:occupineapple_monitor_toggle('stop');\"><strong>Stop Monitor</strong></a><br /><br />";
	
	echo "mdk3";
	echo "&nbsp;<font color=\"lime\"><strong>installed</strong></font><br />";

	if ($is_mdk3_running)
	{
		echo "Occupineapple <span id=\"mdk3_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"mdk3_link\" href=\"javascript:occupineapple_mdk3_toggle('stop');\"><strong>Stop</strong></a> ";
	}
	else
	{ 
		echo "Occupineapple <span id=\"mdk3_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"mdk3_link\" href=\"javascript:occupineapple_mdk3_toggle('start');\"><strong>Start</strong></a> "; 
	}
	
	if($is_mdk3_running)
		echo '<select class="occupineapple" disabled="disabled" id="list" name="list">';
	else
		echo '<select class="occupineapple" id="list" name="list">';
	
	echo '<option>--</option>';
	$lists_list = array_reverse(glob($directory."includes/lists/*"));

	for($i=0;$i<count($lists_list);$i++)
	{
		if($occupineapple_run == basename($lists_list[$i]))
			echo '<option selected value="'.basename($lists_list[$i]).'">'.basename($lists_list[$i]).'</option>';
		else
			echo '<option value="'.basename($lists_list[$i]).'">'.basename($lists_list[$i]).'</option>';
	}
	echo '</select> [<a id="refresh_list" href="javascript:occupineapple_refresh_list();">Refresh</a>] <br /><br />';

	if ($is_mdk3_onboot) 
	{
		echo "Autostart <span id=\"boot_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:occupineapple_boot_toggle('disable');\"><strong>Disable</strong></a><br />";
	}
	else 
	{ 
		echo "Autostart <span id=\"boot_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:occupineapple_boot_toggle('enable');\"><strong>Enable</strong></a><br />"; 
	}
}
else
{
	echo "mdk3";
	echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font><br /><br />";
	
	echo "Install to <a id=\"install_int\" href=\"javascript:occupineapple_install('internal');\">Internal Storage</a> or <a id=\"install_usb\" href=\"javascript:occupineapple_install('usb');\">USB Storage</a>";
		
	exit();	
}

?>
</div>
</div>

<div id="tabs" class="tab">
	<ul>
		<li><a id="Output_link" class="selected" href="#Output">Output</a></li>
		<li><a id="Editor_link" href="#Editor">Editor</a></li>
		<li><a id="Configuration_link" href="#Conf">Configuration</a></li>
	</ul>
	
<div id="Output">
	[<a id="refresh" href="javascript:occupineapple_refresh();">Refresh</a>]<br /><br />
	<textarea readonly class="occupineapple" id='occupineapple_output' name='occupineapple_output' cols='85' rows='29'></textarea>
</div>

<div id="Editor">
	<table id="occupineapple" class="grid" cellspacing="0">
		<tr>
			<td>List: </td>
			<td>
				<select class="occupineapple" id="list_editor" name="list_editor">
				<option>--</option>
				<?php
					$lists_list = array_reverse(glob($directory."includes/lists/*"));

					for($i=0;$i<count($lists_list);$i++)
					{
						echo '<option value="'.basename($lists_list[$i]).'">'.basename($lists_list[$i]).'</option>';
					}
				?>
				</select> [<a id="delete_list" href="javascript:occupineapple_delete_list();">Delete List</a>]
			</td>
		</tr>
		<tr>
			<td>Name: </td>
			<td>
				<input class="occupineapple" type="text" id="list_name" name="list_name" value="" size="50"> [<a id="new_list" href="javascript:occupineapple_new_list();">New List</a>] <span id="error_text"></span>
			</td>
		</tr>	
		<tr>
			<td>&nbsp;</td>
			<td>
				<textarea class="occupineapple" id='list_content' name='list_content' cols='114' rows='29'></textarea><br/><br/>
				[<a id="save_list" href="javascript:occupineapple_save_list();">Save List</a>]	
			</td>
		</tr>
	</table>
</div>

<div id="Conf">
	[<a id="config" href="javascript:occupineapple_set_config();">Save</a>]<br />
	<div id="occupineapple_content_conf"></div>
</div>

</div>
<br />
Auto-refresh <select class="occupineapple" id="occupineapple_auto_time">
	<option value="1000">1 sec</option>
	<option value="5000">5 sec</option>
	<option value="10000">10 sec</option>
	<option value="15000">15 sec</option>
	<option value="20000">20 sec</option>
	<option value="25000">25 sec</option>
	<option value="30000">30 sec</option>
</select> <a id="occupineapple_auto_refresh" href="javascript:void(0);"><font color="red">Off</font></a>
