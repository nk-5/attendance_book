<?php


echo htmlspecialchars($_GET['in_time'],ENT_QUOTES,"utf-8");


// $in_time = htmlspecialchars($_GET['in_time'],ENT_QUOTES,"utf-8");

// if($in_time < '12:00'){
// 	$data = '○';
// }else{
// 	$data = '△';
// }

// echo $data;