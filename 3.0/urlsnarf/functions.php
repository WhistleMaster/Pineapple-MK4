<?php
	
function replace_tags($tags = array(), $buffer) 
{
	foreach ($tags as $tag => $data)
	{
		$buffer = str_replace("%%".$tag."%%", $data, $buffer);
	}
	
	return $buffer;
}
	
?>