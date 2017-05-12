<?php
try{
	list($a[], , $a[]) = [1, 2, 3];
	var_dump($a);
}catch(Exception $e){
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}finally{
    echo "php7list -try-catch-finally";
}


?>
