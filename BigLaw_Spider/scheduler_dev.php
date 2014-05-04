<?php
	include_once 'common.php';
	include_once 'v_court_list.php';
	  
	//請求參數列表
	$sdate = getrequest('sdate', ''); //開始日
	$edate = getrequest('edate', ''); //結束日
	  
	//v_court
	//請參見v_court_list.php
	  
	//v_sys //審判別，A:行政  M:刑事  V:民事  P:公懲  S:訴願
	
	for($courtno = 0; $courtno < count($M_v_court_list); $courtno++)
	{
	
	$casecountquery = array(
		'type' => 'casecount',
		'v_court' => $M_v_court_list[$courtno],
		'v_sys' => 'M',
		'sdate' => $sdate,
		'edate' => $edate
	);
	$casecountquery = http_build_query($casecountquery);
	//$casecountquery = urldecode($casecountquery);
	
	//確定案件數量
	$ch = curl_init();
	//curl_setopt($ch, CURLOPT_URL, 'http://www.meigic.tw:1888/BigLaw_Spider/spider.php'.'?'.$casecountquery);
	//curl_setopt($ch, CURLOPT_URL, 'http://www.meigic.tw:1888/BigLaw_Spider/spider.php?type=casecount&v_court=TPH%20%E8%87%BA%E7%81%A3%E9%AB%98%E7%AD%89%E6%B3%95%E9%99%A2&v_sys=M&sdate=20140401&edate=20140419');
	$dir =  "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	$spiderurl = $dir.'/'.'spider.php';
	
	curl_setopt($ch, CURLOPT_URL, $spiderurl);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $casecountquery);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //return raw data
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'Origin: http://jirs.judicial.gov.tw'));
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.107 Safari/537.36');
	
	$casecount = curl_exec($ch);
    curl_close($ch);
	
	//取得案件內容
	$nodes = array($url1, $url2, $url3);
	$node_count = count($nodes);

	$curl_arr = array();
	$master = curl_multi_init();

	for($i = 0; $i < $node_count; $i++)
	{
		$url =$nodes[$i];
		$curl_arr[$i] = curl_init($url);
		curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
		curl_multi_add_handle($master, $curl_arr[$i]);
	}

	do {
		curl_multi_exec($master,$running);
	} while($running > 0);


	for($i = 0; $i < $node_count; $i++)
	{
		$results[] = curl_multi_getcontent  ( $curl_arr[$i]  );
	}
	print_r($results);
	
	
	//OLD
	for($i = 0; $i < $casecount; $i++)
	{
		$casequery = array(
			'type' => 'casecontext',
			'v_court' => $M_v_court_list[$courtno],
			'v_sys' => 'M',
			'sdate' => $sdate,
			'edate' => $edate,
			'id' => $i+1,
			'format' => 'plaintext'
		);
		$casequery = http_build_query($casequery);
		
		$casech = curl_init();
		curl_setopt($casech, CURLOPT_URL, $spiderurl);
		curl_setopt($casech, CURLOPT_POST, true);
		curl_setopt($casech, CURLOPT_POSTFIELDS, $casequery);
		curl_setopt($casech, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($casech, CURLOPT_RETURNTRANSFER, true); //return raw data
		curl_setopt($casech, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'Origin: http://jirs.judicial.gov.tw'));
		curl_setopt($casech, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.107 Safari/537.36');
		$caseresult = curl_exec($casech);
		curl_close($casech);
		
		echo $caseresult;
		echo '<hr>';
	}
	
	}
?>