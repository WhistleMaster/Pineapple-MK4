<?php

function remoteFileExists($url)
{
	if (@fopen($url,"r"))  
	    return TRUE;  
	else   
	    return FALSE;
} 
	
?>