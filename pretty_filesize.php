<?php

// your code goes here

function pretty_filesize($size){
	$pretty='bytes';
	if($size > 1024){$size=$size/1024;$pretty='KB';	}
	if($size > 1024){$size=$size/1024;$pretty='MB';	}
	if($size > 1024){$size=$size/1024;$pretty='GB';	}
	//
	$size=number_format($size, 2);
	return $str=$size.' '.$pretty;
}

//echo pretty_filesize(1859584 );
//print: 1.77 MB
?>
