<?php

require("/pineapple/components/infusions/sitesurvey/handler.php");

global $directory;

require($directory."includes/vars.php");
require($directory."includes/iwlist_parser.php");

if(isset($_GET['monitor']))
{
	echo '<select class="sitesurvey" id="monitorInterfaces" name="monitorInterfaces">';
	echo '<option value="">--</option>';
	foreach($monitorInterfaces as $value) { echo '<option value="'.$value.'">'.$value.'</option>'; }
	echo '</select>';
}

if(isset($_GET['interface']))
{
	echo '<select class="sitesurvey" id="interfaces" name="interfaces">';
	foreach($interfaces as $value) { echo '<option value="'.$value.'">'.$value.'</option>'; }
	echo '</select>';
}

if(isset($_GET['available_ap']))
{
	if (isset($_GET['int'])) $interface = $_GET['int'];
	
	// List APs
	$iwlistparse = new iwlist_parser();
	$p = $iwlistparse->parseScanDev($interface);

	if(!empty($p))
	{
		echo '<table id="sitesurvey-survey-grid" class="grid" cellspacing="0">';
		echo '<tr class="header">';
		echo '<td>SSID</td>';
		echo '<td colspan="2">Quality level</td>';
		echo '<td>Ch</td>';
		echo '<td>Encryption</td>';
		echo '<td>Cipher</td>';
		echo '<td>Auth</td>';
		echo '</tr>';
	}
	else
	{
		echo "<em>No data...</em>";
	}

	for($i=1;$i<=count($p[$interface]);$i++)
	{
		$quality = $p[$interface][$i]["Quality"];
	
		if($quality <= 25) $graph = "red";
		else if($quality <= 50) $graph = "yellow";
		else if($quality <= 100) $graph = "green";
	
		echo '<tr class="odd">';
	
		echo '<td>'.$p[$interface][$i]["ESSID"].'</td>';
	
		echo "<td>".$quality."%</td>";
		echo "<td width='150'>";
		echo '<div class="graph-border">';
		echo '<div class="graph-bar" style="width: '.$quality.'%; background: '.$graph.';"></div>';
		echo '</div>';
		echo "</td>";
		echo '<td>'.$p[$interface][$i]["Channel"].'</td>';
		
		if($p[$interface][$i]["Encryption key"] == "on")
		{
			$WPA = strstr($p[$interface][$i]["IE"], "WPA Version 1");
			$WPA2 = strstr($p[$interface][$i]["IE"], "802.11i/WPA2 Version 1");
		
			$auth_type = str_replace("\n"," ",$p[$interface][$i]["Authentication Suites (1)"]);
			$auth_type = implode(' ',array_unique(explode(' ', $auth_type)));
		
			$cipher = $p[$interface][$i]["Pairwise Ciphers (2)"] ? $p[$interface][$i]["Pairwise Ciphers (2)"] : $p[$interface][$i]["Pairwise Ciphers (1)"];
			$cipher = str_replace("\n"," ",$cipher);
			$cipher = implode(',',array_unique(explode(' ', $cipher)));
	
			if($WPA2 != "" && $WPA != "")
				echo '<td>WPA,WPA2</td>';
			else if($WPA2 != "")
				echo '<td>WPA2</td>';
			else if($WPA != "")
				echo '<td>WPA</td>';
			else
				echo '<td>WEP</td>';
			
			echo '<td>'.$cipher.'</td>';
			echo '<td>'.$auth_type.'</td>';
		}
		else
		{
			echo '<td>None</td>';
			echo '<td>&nbsp;</td>';
			echo '<td>&nbsp;</td>';
		}

		echo '</tr>';
	}
}

?>