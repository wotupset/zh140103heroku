<?php 
header('Content-Type: application/javascript; charset=utf-8');
$data = "干我屁事 北七"; 
printf("%-15s %-10s %s\n", "類型", "長度", "hash值"); 
foreach(hash_algos() as $v) { 
	$r = hash($v, $data); 
	printf("%-12s %3d %s\n", $v, strlen($r), $r); 
} 
?> 