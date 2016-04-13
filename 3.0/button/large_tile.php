<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/button/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/button/includes/js/infusion.js'></script>

<style>@import url('/components/infusions/button/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ button_init(); });
</script>

<form method='POST' id='myform' action='/components/infusions/button/functions.php?button_update_conf' onSubmit='$(this).AJAXifyForm(button_update_message); return false;'>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $name; ?> - v<?php echo $version; ?>&nbsp;<span id="button" class="refresh_text"></span></div>
<div class=sidePanelContent><br/><input type='submit' value='Save'/></div>
</div>
<?php

for($i=0;$i<4;$i++)
{
	$status = exec("uci get system.@button[".($i+1)."].handler");
	
	if($status == $no_handler) $_wpsx[$i] = 0;
	else if($status == $karma_handler) $_wpsx[$i] = 1;
	else if($status == $dnsspoof_handler) $_wpsx[$i] = 2;
	else if($status == $ssh_handler) $_wpsx[$i] = 3;
	else if($status == $reboot_handler) $_wpsx[$i] = 4;
	else if($status == $script1_handler) $_wpsx[$i] = 5;
	else if($status == $script2_handler) $_wpsx[$i] = 6;
	else if($status == $script3_handler) $_wpsx[$i] = 7;
	else if($status == $script4_handler) $_wpsx[$i] = 8;
	else if($status == $default_handler) $_wpsx[$i] = 9;
}

?>

<div id="tabs" class="tab">
	<ul>
		<li><a id="Actions_link" class="selected" href="#Actions">Actions</a></li>
		<li><a id="Custom_link" href="#Custom">Custom Script</a></li>
	</ul>

<div id="Actions">
	<table id="button" class="fields">
	<tbody>
		<tr><td colspan="2" class="title indent1">When WPS button pushed for...</td></tr>
		<tr><td colspan="2" class="title indent1">&nbsp;</td></tr>
		<?php

			for($i=0;$i<sizeof($_wpsx_time);$i++)
			{
				echo '<tr><td class="title indent2"><label for="_wpsx_b'.$i.'">'.$_wpsx_time[$i].'</label></td>'."\n";
				echo '<td class="module_content">'."\n";
				echo '<select class="button" name="_wpsx[]" id="_wpsx_b'.$i.'">'."\n";

				for($j=0;$j<sizeof($_wpsx_values);$j++)
				{
					if($_wpsx[$i] == $j)
						echo '<option value="'.$j.'" selected>'.$_wpsx_values[$j].'</option>'."\n";
					else
						echo '<option value="'.$j.'">'.$_wpsx_values[$j].'</option>'."\n";				
				}

				echo '</select>'."\n";
				
				echo '</td></tr>'."\n";
			}

		?>
	</tbody>
	</table>
</div>

<div id="Custom">
	<table id="button" class="fields">
	<tbody>
		<tr>
			<td class="title indent2"><label for="_wpsx_script1">Custom Script 1</label></td>
			<td class="module_content"><textarea class="button" name="_newdata[]" id="_wpsx_script1"><?=file_get_contents($directory."includes/scripts/wpsScript1.sh")?></textarea></td>
		</tr>
		<tr>
			<td class="title indent2"><label for="_wpsx_script2">Custom Script 2</label></td>
			<td class="module_content"><textarea class="button" name="_newdata[]" id="_wpsx_script2"><?=file_get_contents($directory."includes/scripts/wpsScript2.sh")?></textarea></td>
		</tr>
		<tr>
			<td class="title indent2"><label for="_wpsx_script3">Custom Script 3</label></td>
			<td class="module_content"><textarea class="button" name="_newdata[]" id="_wpsx_script3"><?=file_get_contents($directory."includes/scripts/wpsScript3.sh")?></textarea></td>
		</tr>
		<tr>
			<td class="title indent2"><label for="_wpsx_script4">Custom Script 4</label></td>
			<td class="module_content"><textarea class="button" name="_newdata[]" id="_wpsx_script4"><?=file_get_contents($directory."includes/scripts/wpsScript4.sh")?></textarea></td>
		</tr>
		<tr>
			<td class="title indent2"><label for="_wpsx_script5">Default Pineapple Script</label></td>
			<td class="module_content"><textarea class="button" name="_newdata[]" id="_wpsx_script5"><?=file_get_contents('/etc/pineapple/wpsScript.sh')?></textarea></td>
		</tr>
	</tbody>
	</table>
</div>

</div>
</form>