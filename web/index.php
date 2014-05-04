<?php
	//keyword search
	$url = 'http://'.$_SERVER['HTTP_HOST'].'/API/index.php'.'?action=keyword'.'&'.'keyword='.$_GET['keyword'];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //return raw data
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'Origin: http://jirs.judicial.gov.tw'));
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.107 Safari/537.36');
	$result = curl_exec($ch);
    curl_close($ch);
	$list = json_decode($result, true);
	
	//redirect to list
	session_start();
	$_SESSION['keyword'] = $_GET['keyword'];
	$_SESSION['list'] = $list;
	header('Location: '.'list.php');
?>