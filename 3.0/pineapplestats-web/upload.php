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
		
		   //if(isset($_POST['Data_Timestamp'])) $Data_Timestamp = $_POST['Data_Timestamp']; else $Data_Timestamp = "CURRENT_TIMESTAMP()";
		   $Data_Timestamp = "CURRENT_TIMESTAMP()";
		   if(isset($_POST['Station_SSID'])) $Station_SSID = $_POST['Station_SSID']; else $Station_SSID = "N/A";
		   if(isset($_POST['Station_MAC'])) $Station_MAC = $_POST['Station_MAC']; else $Station_MAC = "N/A";
		   if(isset($_POST['Station_Signal'])) $Station_Signal = $_POST['Station_Signal']; else $Station_Signal = "N/A";
		   if(isset($_POST['Station_Signal_Quality'])) $Station_Signal_Quality = $_POST['Station_Signal_Quality']; else $Station_Signal_Quality = "N/A";
		   
	       if ($stmt = $mysqli->prepare("SELECT Pineapple_ID FROM PineappleStats_Pineapples WHERE Pineapple_Number = ? LIMIT 1")) 
	       { 
			   $stmt->bind_param('s', $Pineapple_Number);
			   $stmt->execute(); 
			   $stmt->store_result();
			   $stmt->bind_result($Pineapple_ID); 
			   $stmt->fetch();
 
			   if($stmt->num_rows == 0)
			   {
				   $mysqli->query("INSERT INTO `PineappleStats_Pineapples` VALUES ('', '".$Pineapple_Number."', '".$Pineapple_Name."', '".$Pineapple_MAC."', '".$Pineapple_Latitude."', '".$Pineapple_Longitude."', ".$Data_Timestamp.", 'N/A')");
			   
				   $Pineapple_ID = $mysqli->insert_id;
			   }
		
			   $mysqli->query("INSERT INTO `PineappleStats_Data` VALUES ('', ".$Data_Timestamp.", 
  														  '".$Pineapple_ID."',
  														  '".$Station_SSID."',
  														  '".$Station_MAC."', 
  														  '".$Station_Signal."', 
  														  '".$Station_Signal_Quality."')");
	 	    }
			
 	       if ($stmt = $mysqli->prepare("SELECT `Stats_ID`, `Station_X` FROM PineappleStats_Stats WHERE `Station_MAC` = ? AND (UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) - UNIX_TIMESTAMP(`Stats_Timestamp`)) < 18000"))
 	       {
 			   $stmt->bind_param('s', $Station_MAC);
 			   $stmt->execute(); 
 			   $stmt->store_result();
 			   $stmt->bind_result($Stats_ID, $Station_X);
 			   $stmt->fetch();
 
 			   if($stmt->num_rows == 0)
 			   {
 				   $mysqli->query("INSERT INTO `PineappleStats_Stats` VALUES ('', ".$Data_Timestamp.", '".$Pineapple_ID."', '".$Station_MAC."', '1')");
 			   }
			   else
			   {
				   if ($stmt = $mysqli->prepare("SELECT `Stats_ID`, `Station_X` FROM PineappleStats_Stats WHERE `Station_MAC` = ? AND (UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) - UNIX_TIMESTAMP(`Stats_Timestamp`)) < 18000 AND `Station_X` <> 1"))
				   {
					   $stmt->bind_param('s', $Station_MAC);
					   $stmt->execute(); 
					   $stmt->store_result();
					   $stmt->bind_result($Stats_ID, $Station_X);
					   $stmt->fetch();
				   
					   if($stmt->num_rows != 0)
					   {
						   $Station_X = $Station_X + 1;
						   $mysqli->query("UPDATE `PineappleStats_Stats` SET Station_X='".$Station_X."', Stats_Timestamp=".$Data_Timestamp." WHERE Stats_ID='".$Stats_ID."'");
					   }
					   else
					   {
						   $mysqli->query("INSERT INTO `PineappleStats_Stats` VALUES ('', ".$Data_Timestamp.", '".$Pineapple_ID."', '".$Station_MAC."', '2')");
					   }
				   }
			   }
 	 	    }
		}
    }
}
	
?>
