<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/nmap/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/nmap/includes/js/infusion.js'></script>

<style>@import url('/components/infusions/nmap/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ nmap_init(); });
</script>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $name; ?> - v<?php echo $version; ?>&nbsp;<span id="nmap" class="refresh_text"></span></div>
<div class=sidePanelContent><br/>
<?php
if($is_nmap_installed)
{
	echo "nmap";
	echo "&nbsp;<font color=\"lime\"><strong>installed</strong></font><br />";
}
else
{
	echo "nmap";
	echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font><br /><br />";
	
	echo "Install to <a id=\"install_int\" href=\"javascript:nmap_install('internal');\">Internal Storage</a> or <a id=\"install_usb\" href=\"javascript:nmap_install('usb');\">USB Storage</a>";
		
	exit();
}

?>
</div>
</div>

<div id="tabs" class="tab">
	<ul>
		<li><a class="selected" href="#General">General</a></li>
		<li><a href="#Scan">Scan</a></li>
		<li><a href="#Ping">Ping</a></li>
		<li><a href="#Target">Target</a></li>
		<li><a href="#Other">Other</a></li>
	</ul>
	
<div id="General">
	<table id="nmap" class="grid" cellspacing="0">
		<tr>
			<td>Target: </td>
			<td>
			<?php
				if($target_run != "")
					echo '<input class="nmap" type="text" id="target" name="target" value="'.$target_run.'" size="70">';
				else
					echo '<input class="nmap" type="text" id="target" name="target" value="" size="70">';
			?>
			</td>
			
			<td>Profile: </td>
			<td><select class="nmap" id="profile" name="profile">
			<option>--</option>
			<?php
				foreach($profiles as $key => $value)
				{
					if($profile_run != "" && $profile_run == $key)
						echo '<option selected value="'.$value.'">'.$key.'</option>';
					else
						echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>
	</table>
</div>

<div id="Scan">
	<table id="nmap" class="grid" cellspacing="0">
		<tr>
			<td>Timing: </td>
			<td><select class="nmap" id="timing" name="timing">
			<option>--</option>
			<?php
				foreach($timmings as $key => $value)
				{
					echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>
		<tr>
			<td>TCP scan: </td>
			<td><select class="nmap" id="tcp" name="tcp">
			<option>--</option>
			<?php
				foreach($tcp_scans as $key => $value)
				{
					echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>
		<tr>
			<td>Non-TCP scan: </td>
			<td><select class="nmap" id="nontcp" name="nontcp">
			<option>--</option>
			<?php
				foreach($non_tcp_scans as $key => $value)
				{
					echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>
		<tr>
			<td colspan="2">
			<?php
				foreach($scan_options as $key => $value)
				{
					echo '<input class="nmap" type="checkbox" name="'.$key.'" value="'.$value.'" />&nbsp;'.$key."<br />";
				}
			?>
			</td>
		</tr>
	</table>
</div>

<div id="Ping">
	<table id="nmap" class="grid" cellspacing="0">
		<tr>
			<td>
			<?php
				foreach($ping_options as $key => $value)
				{
					echo '<input class="nmap" type="checkbox" name="'.$key.'" value="'.$value.'" />&nbsp;'.$key."<br />";
				}
			?>
			</td>
		</tr>
	</table>
</div>

<div id="Target">
	<table id="nmap" class="grid" cellspacing="0">
		<tr>
			<td>
			<?php
				foreach($target_options as $key => $value)
				{
					echo '<input class="nmap" type="checkbox" name="'.$key.'" value="'.$value.'" />&nbsp;'.$key."<br />";
				}
			?>
			</td>
		</tr>
	</table>
</div>

<div id="Other">
	<table id="nmap" class="grid" cellspacing="0">
		<tr>
			<td>
			<?php
				foreach($other_options as $key => $value)
				{
					echo '<input class="nmap" type="checkbox" name="'.$key.'" value="'.$value.'" />&nbsp;'.$key."<br />";
				}
			?>
			</td>
		</tr>
	</table>
</div>

<div style="border-top: 1px solid black;">
<?php
if($cmd_run != "")
	echo 'Command: <input class="nmap" type="text" id="command" name="command" value="'.$cmd_run.'" size="115"><br /><br />';
else	
	echo 'Command: <input class="nmap" type="text" id="command" name="command" value="nmap " size="115"><br /><br />';
?>

<span id="nmap_control">
	<?php
	if($is_nmap_running)
	{
		echo '<a id="scan" href="javascript:nmap_scan_toggle(\'cancel\');"><font color="red"><strong>Cancel</strong></font></a>';
	}
	else
	{
		echo '<a id="scan" href="javascript:nmap_scan_toggle(\'scan\');"><font color="lime"><strong>Scan</strong></font></a>';
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
	<textarea readonly class="nmap" id='nmap_output' name='nmap_output' cols='85' rows='29'></textarea>
</div>

<div id="History">
	[<a id="refresh" href="javascript:nmap_refresh_history();">Refresh</a>]<br />
	<div id="nmap_content"></div>
</div>

</div>