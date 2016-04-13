<?php

require("/pineapple/components/infusions/status/handler.php");
require("/pineapple/components/infusions/status/functions.php");

global $directory;

require($directory."includes/vars.php");

echo '<fieldset class="status">';

echo '<legend class="status">CPU</legend>';

$stat1 = GetCoreInformation(); sleep(1); $stat2 = GetCoreInformation();
$data = GetCpuPercentages($stat1, $stat2);
$cpu_load_ptg = 100 - $data['cpu0']['idle'];
$cpu_load_all = exec("uptime | awk -F 'average:' '{ print $2}'");

echo '<div class="setting">';
echo '<div class="label">Load Average</div>';
echo '<span id="cpu_load"><div class="meter"><div class="bar" style="width: '.$cpu_load_ptg.'%;"></div><div class="text">'.$cpu_load_ptg.'%</div></div>'.$cpu_load_all.'</span>&nbsp;';
echo '</div>';

echo '</fieldset>';
echo '<br />';
echo '<fieldset class="status">';

echo '<legend class="status">Memory</legend>';

$mem_total = exec("free | grep \"Mem:\" | awk '{ print $2 }'");
$mem_used = exec("free | grep \"Mem:\" | awk '{ print $3 }'");
$mem_free = exec("free | grep \"Mem:\" | awk '{ print $4 }'");

$mem_free_ptg = round(($mem_free / $mem_total) * 100);
$mem_used_ptg = 100 - $mem_free_ptg;

echo '<div class="setting">';
echo '<div class="label">Total Available</div>';
echo '<span id="mem_total">'.kbytes_to_string($mem_total).'</span>&nbsp;';
echo '</div>';

echo '<div class="setting">';
echo '<div class="label">Free</div>';
echo '<span id="mem_free"><div class="meter"><div class="bar" style="width: '.$mem_free_ptg.'%;"></div><div class="text">'.$mem_free_ptg.'%</div></div>'.kbytes_to_string($mem_free).'</span>&nbsp;';
echo '</div>';

echo '<div class="setting">';
echo '<div class="label">Used</div>';
echo '<span id="mem_used"><div class="meter"><div class="bar" style="width: '.$mem_used_ptg.'%;"></div><div class="text">'.$mem_used_ptg.'%</div></div>'.kbytes_to_string($mem_used).'</span>&nbsp;';
echo '</div>';

echo '</fieldset>';
echo '<br />';
echo '<fieldset class="status">';

echo '<legend class="status">Swap</legend>';

$swap_total = exec("free | grep \"Swap:\" | awk '{ print $2 }'");
$swap_used = exec("free | grep \"Swap:\" | awk '{ print $3 }'");
$swap_free = exec("free | grep \"Swap:\" | awk '{ print $4 }'");

if($swap_total != 0) $swap_free_ptg = round(($swap_free / $swap_total) * 100); else $swap_free_ptg = 0;
$swap_used_ptg = 100 - $swap_free_ptg;

if($swap_total != 0)
{
	echo '<div class="setting">';
	echo '<div class="label">Total Available</div>';
	echo '<span id="mem_total">'.kbytes_to_string($swap_total).'</span>&nbsp;';
	echo '</div>';

	echo '<div class="setting">';
	echo '<div class="label">Free</div>';
	echo '<span id="mem_free"><div class="meter"><div class="bar" style="width: '.$swap_free_ptg.'%;"></div><div class="text">'.$swap_free_ptg.'%</div></div>'.kbytes_to_string($swap_free).'</span>&nbsp;';
	echo '</div>';

	echo '<div class="setting">';
	echo '<div class="label">Used</div>';
	echo '<span id="mem_used"><div class="meter"><div class="bar" style="width: '.$swap_used_ptg.'%;"></div><div class="text">'.$swap_used_ptg.'%</div></div>'.kbytes_to_string($swap_used).'</span>&nbsp;';
	echo '</div>';
}
else
{
	echo '<div class="setting">';
	echo '<div class="label">Total Available</div>';
	echo '<span id="mem_total"><em>No Swap</em></span>&nbsp;';
	echo '</div>';
}

echo '</fieldset>';
echo '<br />';
echo '<fieldset class="status">';

echo '<legend class="status">Storage</legend>';

$df = explode("\n", trim(shell_exec("df | grep -v \"Filesystem\"")));

for($i=0;$i<count($df);$i++)
{
	$df_name = exec("df | grep -v \"Filesystem\" | grep \"".$df[$i]."\" | awk '{ print $1}'");
	$df_mount = exec("df | grep -v \"Filesystem\" | grep \"".$df[$i]."\" | awk '{ print $6}'");
	$df_total = exec("df | grep -v \"Filesystem\" | grep \"".$df[$i]."\" | awk '{ print $2}'");
	$df_used = exec("df | grep -v \"Filesystem\" | grep \"".$df[$i]."\" | awk '{ print $3}'");
	$df_used_ptg = exec("df | grep -v \"Filesystem\" | grep \"".$df[$i]."\" | awk '{ print $5}'");
	
	echo '<div class="setting">';
	echo '<div class="label">['.$df_mount.']</div>';
	echo '<span id="df_used"><div class="meter"><div class="bar" style="width: '.$df_used_ptg.';"></div><div class="text">'.$df_used_ptg.'</div></div>'.kbytes_to_string($df_used).'/'.kbytes_to_string($df_total).'</span>&nbsp;';
	echo '</div>';
}

echo '</fieldset>';

?>
