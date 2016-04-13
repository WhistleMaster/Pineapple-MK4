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
	    
	   if($stmt->num_rows != 1)
	   {	
		   $mysqli->query("INSERT INTO `PineappleStats_Tokens` (`Token_ID`, `Token_Number`) VALUES ('', '".$token."')");
	   }
	}
}
	
?>
