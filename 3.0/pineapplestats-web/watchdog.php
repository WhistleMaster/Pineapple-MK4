<?php

include('includes/db_connect.php'); 
include('includes/functions.php'); 

if(isset($_POST['token'])) $token = $_POST['token']; else $token = "";

if($token != "")
{
    if ($stmt = $mysqli->prepare("SELECT Token_Number FROM PineappleStats_Tokens WHERE Token_Number = ? LIMIT 1")) 
    { 
       $stmt->bind_param('s', $token);
       $stmt->execute(); 
       $stmt->store_result();
       $stmt->bind_result($Token_Number); 
       $stmt->fetch();
 
	   if($stmt->num_rows == 1)
	   {
		   if(isset($_POST['Pineapple_Number'])) $Pineapple_Number = $_POST['Pineapple_Number']; else $Pineapple_Number = "N/A";
		   if(isset($_POST['Pineapple_Name'])) $Pineapple_Name = $_POST['Pineapple_Name']; else $Pineapple_Name = "N/A";
		   if(isset($_POST['Pineapple_MAC'])) $Pineapple_MAC = $_POST['Pineapple_MAC']; else $Pineapple_MAC = "N/A";
		   if(isset($_POST['Pineapple_Latitude'])) $Pineapple_Latitude = $_POST['Pineapple_Latitude']; else $Pineapple_Latitude = "N/A";
		   if(isset($_POST['Pineapple_Longitude'])) $Pineapple_Longitude = $_POST['Pineapple_Longitude']; else $Pineapple_Longitude = "N/A";
		
   			if(isset($_POST['Pineapple_IP'])) $Pineapple_IP = $_POST['Pineapple_IP']; else $Pineapple_IP = "N/A";

   			//if(isset($_POST['Data_Timestamp'])) $Data_Timestamp = $_POST['Data_Timestamp']; else $Data_Timestamp = "CURRENT_TIMESTAMP()";
			$Data_Timestamp = "CURRENT_TIMESTAMP()";

			if ($stmt = $mysqli->prepare("SELECT Pineapple_ID FROM PineappleStats_Pineapples WHERE Pineapple_Number = ? LIMIT 1")) 
	       	{ 
				$stmt->bind_param('s', $Pineapple_Number);
				$stmt->execute(); 
				$stmt->store_result();
				$stmt->bind_result($Pineapple_ID); 
				$stmt->fetch();
 
				if($stmt->num_rows == 0)
				{
					$mysqli->query("INSERT INTO `PineappleStats_Pineapples` VALUES ('', '".$Pineapple_Number."', '".$Pineapple_Name."', '".$Pineapple_MAC."', '".$Pineapple_Latitude."', '".$Pineapple_Longitude."', ".$Data_Timestamp.", '".$Pineapple_IP."')");
				}
				else
				{
					$mysqli->query("UPDATE `PineappleStats_Pineapples` SET Pineapple_LastReport=".$Data_Timestamp.", Pineapple_IP='".$Pineapple_IP."', Pineapple_Latitude='".$Pineapple_Latitude."', Pineapple_Longitude='".$Pineapple_Longitude."' WHERE Pineapple_ID='".$Pineapple_ID."'");
				}
				
				$mysqli->query("INSERT INTO `PineappleStats_Uptime` VALUES ('', '".$Pineapple_ID."', ".$Data_Timestamp.")");
			}
		}
	}
}
	
?>