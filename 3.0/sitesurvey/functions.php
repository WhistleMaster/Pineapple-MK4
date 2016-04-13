<?php

function dataSize($path)
{
    $blah = exec( "/usr/bin/du -sch $path | tail -1 | awk {'print $1'}" );
    return $blah;
}

function replace_tags($tags = array(), $buffer) 
{
	foreach ($tags as $tag => $data)
	{
		$buffer = str_replace("%%".$tag."%%", $data, $buffer);
	}
	
	return $buffer;
}

?>