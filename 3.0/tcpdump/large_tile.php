<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/tcpdump/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/tcpdump/includes/js/infusion.js'></script>

<style>@import url('/components/infusions/tcpdump/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ tcpdump_init(); });
</script>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $name; ?> - v<?php echo $version; ?>&nbsp;<span id="tcpdump" class="refresh_text"></span></div>
<div class=sidePanelContent><br/>
<?php
if($is_tcpdump_installed)
{
	echo "tcpdump";
	echo "&nbsp;<font color=\"lime\"><strong>installed</strong></font><br />";
}
else
{
	echo "tcpdump";
	echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font><br /><br />";
	
	echo "Install to <a id=\"install_int\" href=\"javascript:tcpdump_install('internal');\">Internal Storage</a> or <a id=\"install_usb\" href=\"javascript:tcpdump_install('usb');\">USB Storage</a>";
		
	exit();
}

?>
</div>
</div>

<div id="tabs" class="tab">
	<ul>
		<li><a class="selected" href="#General">General</a></li>
		<li><a href="#Options">Options</a></li>
	</ul>
	
<div id="General">
	<table id="tcpdump" class="grid" cellspacing="0">
		<tr>
			<td>Filter: </td>
			<td><input class="tcpdump" type="text" id="filter" name="filter" value="" size="70"></td>
			
			<td>Interface: </td>
			<td><select class="tcpdump" id="interface" name="interface">
			<option>--</option>
			<?php
				foreach($interfaces as $key => $value)
				{
					if($int_run != "" && $int_run == $key)
						echo '<option selected value="'.$value.'">'.$key.'</option>';
					else
						echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>	
		<tr>
			<td>Type: </td><td><a href="javascript:tcpdump_append_filter('host');">host</a> <a href="javascript:tcpdump_append_filter('net');">net</a> <a href="javascript:tcpdump_append_filter('portrange');">portrange</a> <a href="javascript:tcpdump_append_filter('port');">port</a> <a href="javascript:tcpdump_append_filter('gateway');">gateway</a> <a href="javascript:tcpdump_append_filter('mask');">mask</a><td/>
		</tr>
		<tr>
			<td>Dir: </td><td><a href="javascript:tcpdump_append_filter('src');">src</a> <a href="javascript:tcpdump_append_filter('dst');">dst</a> <a href="javascript:tcpdump_append_filter('src or dst');">src or dst</a> <a href="javascript:tcpdump_append_filter('src and dst');">src and dst</a><td/>
		</tr>
		<tr>			
			<td>Proto: </td><td><a href="javascript:tcpdump_append_filter('ip');">ip</a> <a href="javascript:tcpdump_append_filter('proto');">proto</a> <a href="javascript:tcpdump_append_filter('icmp');">icmp</a> <a href="javascript:tcpdump_append_filter('tcp');">tcp</a> <a href="javascript:tcpdump_append_filter('udp');">udp</a> <a href="javascript:tcpdump_append_filter('arp');">arp</a> <a href="javascript:tcpdump_append_filter('ether');">ether</a> <a href="javascript:tcpdump_append_filter('http');">http</a> <a href="javascript:tcpdump_append_filter('ftp');">ftp</a> <a href="javascript:tcpdump_append_filter('smtp');">smtp</a><td/>
		</tr>
		<tr>
			<td>Length: </td><td><a href="javascript:tcpdump_append_filter('less');">less</a> <a href="javascript:tcpdump_append_filter('greater');">greater</a><td/>
		</tr>
		<tr>
			<td>Kind: </td><td><a href="javascript:tcpdump_append_filter('broadcast');">broadcast</a> <a href="javascript:tcpdump_append_filter('multicast');">multicast</a><td/>
		</tr>
		<tr>
			<td>Operator: </td><td><a href="javascript:tcpdump_append_filter('not');">not</a> <a href="javascript:tcpdump_append_filter('and');">and</a> <a href="javascript:tcpdump_append_filter('or');">or</a> <a href="javascript:tcpdump_append_filter('\(');">(</a> <a href="javascript:tcpdump_append_filter('\)');">)</a></td>
		</tr>	
	</table>
</div>

<div id="Options">
	<table id="tcpdump" class="grid" cellspacing="0">
		<tr>
			<td>Verbose: </td>
			<td><select class="tcpdump" id="verbose" name="verbose">
			<option>--</option>
			<?php
				foreach($verbose as $key => $value)
				{
					echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>
		<tr>
			<td>Resolve: </td>
			<td><select class="tcpdump" id="resolve" name="resolve">
			<option>--</option>
			<?php
				foreach($resolve as $key => $value)
				{
					echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>
		<tr>
			<td>Timestamp: </td>
			<td><select class="tcpdump" id="timestamp" name="timestamp">
			<option>--</option>
			<?php
				foreach($timestamp as $key => $value)
				{
					echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>
		<tr>
			<td colspan="2">
			<?php
				foreach($options as $key => $value)
				{
					echo '<input class="tcpdump" type="checkbox" name="'.$key.'" value="'.$value.'" />&nbsp;'.$key."<br />";
				}
			?>
			</td>
		</tr>
	</table>
</div>

<div style="border-top: 1px solid black;">
<?php
if($cmd_run != "")
	echo 'Command: <input class="tcpdump" type="text" id="tcpdump_command" name="tcpdump_command" value="'.$cmd_run.'" size="115"><br /><br />';
else
	echo 'Command: <input class="tcpdump" type="text" id="tcpdump_command" name="tcpdump_command" value="tcpdump " size="115"><br /><br />';
?>

<span id="control">
	<?php
	if($is_tcpdump_running)
	{
		echo '<a id="scan" href="javascript:tcpdump_dump_toggle(\'stop\');"><font color="red"><strong>Stop</strong></font></a>';
	}
	else
	{
		echo '<a id="scan" href="javascript:tcpdump_dump_toggle(\'capture\');"><font color="lime"><strong>Capture</strong></font></a>';
	}
	?>
</span>
</div>

</div>

<div id="tabs2" class="tab">
	<ul>
		<li><a id="Output_link" class="selected" href="#Output">Output</a></li>
		<li><a id="History_link" href="#History">History</a></li>
	</ul>
	
<div id="Output">
	<textarea readonly class="tcpdump" id='tcpdump_output' name='tcpdump_output' cols='85' rows='29'></textarea>
</div>

<div id="History">
	[<a id="refresh" href="javascript:tcpdump_refresh_history();">Refresh</a>]<br />
	<div id="tcpdump_content"></div>
</div>

</div>