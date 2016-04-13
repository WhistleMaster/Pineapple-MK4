<?php

function sec_session_start() 
{
	$session_name = 'pineapplestats_sec_session_id'; 
	$secure = false; 
	$httponly = true;
 
	ini_set('session.use_only_cookies', 1); 
	$cookieParams = session_get_cookie_params(); 
	session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
	session_name($session_name); 
	session_start(); 
	session_regenerate_id(true);
}

function checkbrute($user_id, $mysqli) 
{
   $now = time();
   $valid_attempts = $now - (2 * 60 * 60); 
 
   if ($stmt = $mysqli->prepare("SELECT Attempts_Time FROM PineappleStats_Attempts WHERE Attempts_Login_ID = ? AND Attempts_Time > '$valid_attempts'")) { 
      $stmt->bind_param('i', $user_id); 
      $stmt->execute();
      $stmt->store_result();

      if($stmt->num_rows > 5) 
	  {
         return true;
      } 
	  else 
	  {
         return false;
      }
   }
}

function login($email, $password, $mysqli) 
{

   if ($stmt = $mysqli->prepare("SELECT Login_ID, Login_Username, Login_Password, Login_Salt FROM PineappleStats_Login WHERE Login_Email = ? LIMIT 1")) 
   { 
      $stmt->bind_param('s', $email);
      $stmt->execute(); 
      $stmt->store_result();
      $stmt->bind_result($user_id, $username, $db_password, $salt); 
      $stmt->fetch();
      $password = hash('sha512', $password.$salt);
 
      if($stmt->num_rows == 1) 
	  { 
         if(checkbrute($user_id, $mysqli) == true) 
		 { 
            // Account is locked
            return false;
         } 
		 else 
		 {
         	if($db_password == $password)
			{
				// Password is correct!
				$user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
 
				$user_id = preg_replace("/[^0-9]+/", "", $user_id); // XSS protection as we might print this value
				$_SESSION['user_id'] = $user_id; 
				$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // XSS protection as we might print this value
				$_SESSION['username'] = $username;
				$_SESSION['login_string'] = hash('sha512', $password.$user_browser);

               return true;    
	         } 
			 else 
			 {
	            // Password is not correct
            
				$now = time();
	            $mysqli->query("INSERT INTO PineappleStats_Attempts (Attempts_Login_ID, Attempts_Time) VALUES ('$user_id', '$now')");
            
				return false;
	         }
      	}
      } 
	  else 
	  {
         // No user exists. 
         return false;
      }
   }
}

function login_check($mysqli)
{
	if(isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) 
	{
		$user_id = $_SESSION['user_id'];
		$login_string = $_SESSION['login_string'];
		$username = $_SESSION['username'];
 
		$user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
 
		if ($stmt = $mysqli->prepare("SELECT Login_Password FROM PineappleStats_Login WHERE Login_ID = ? LIMIT 1")) 
		{ 
			$stmt->bind_param('i', $user_id); // Bind "$user_id" to parameter.
			$stmt->execute(); // Execute the prepared query.
			$stmt->store_result();
 
			if($stmt->num_rows == 1) 
			{ 
				$stmt->bind_result($password); // get variables from result.
				$stmt->fetch();
				$login_check = hash('sha512', $password.$user_browser);
				
				if($login_check == $login_string)
				{
					// Logged In!!!!
					return true;
				} 
				else 
				{
					// Not logged in
					return false;
				}
			} 
			else 
			{
            	// Not logged in
				return false;
			}
		} 
		else 
		{
			// Not logged in
			return false;
		}
	} 
	else 
	{
		// Not logged in
		return false;
	}
}

function updateConfigItem($item, $value, $quote=true) 
{
	global $cfg;
	
	if ($quote) 
	{
		$value = '"'.$value.'"';
	}
	
	$i = strpos($cfg, $item);
	
	if ($i === false)
	{
		$i = strpos($cfg, '/** Do not edit below this line. **/');
		$cfg = substr($cfg, 0, $i)."\$conf['".$item."'] = ".$value.";\n".substr($cfg,$i);
	} 
	else 
	{
		$i = strpos($cfg, '=', $i);
		$j = strpos($cfg, "\n", $i);
		$cfg = substr($cfg, 0, $i) . '= ' . $value . ';' . substr($cfg, $j);
	}
}

function updateConfigFile($cfg)
{
	if (is_writeable(CONFIGFILE))
	{
		if ($handle = fopen(CONFIGFILE, 'w')) 
		{
			if (fwrite($handle, $cfg)) 
			{
				return true;
			}
		}
	
		fclose($handle);	
	}
}

function sanitize($input_string, $sanitize_level=3) 
{
	if (is_array($input_string)) 
	{
		foreach ($input_string as $output_key => $output_value) 
		{
			$output_string[$output_key] = sanitize_string($output_value, $sanitize_level);
		}
		unset($output_key, $output_value);
	} 
	else 
	{
		$output_string = sanitize_string($input_string, $sanitize_level);
	}
	
	return $output_string;
}

function sanitize_string($input_string, $sanitize_level) 
{
	if (get_magic_quotes_gpc()) $input_string = stripslashes($input_string);
	
	if ($sanitize_level === 0) 
	{
		$input_string = str_replace(chr(0), " ", $input_string);
	} 
	else 
	{
		$input_string = strip_tags($input_string);
	}
	
	return $input_string;
}

?>