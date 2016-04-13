<?php 

include('includes/db_connect.php'); 
include('includes/functions.php'); 

sec_session_start();
 
if(isset($_POST['email'], $_POST['p'])) { 
   $email = $_POST['email'];
   $password = $_POST['p'];
   if(login($email, $password, $mysqli) == true) {
      // Login success
      header('Location: ./');
   } else {
      // Login failed
      header('Location: ./?error=1');
   }
}

?>