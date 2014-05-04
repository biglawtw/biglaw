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
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //tracking redirecting
    //curl_setopt($ch, CURLOPT_HEADER, true); //enable header output
    //curl_setopt($ch, CURLINFO_HEADER_OUT, true); //enable header tracking
	
	$casecount = curl_exec($ch);
	//$headerSent = curl_getinfo($ch, CURLINFO_HEADER_OUT);
    curl_close($ch);
	
	//echo $_SERVER['HTTP_HOST'];
	//echo $headerSent.'<br/>';
	//echo $M_v_court_list[0];
	//echo 'v_court='.$M_v_court_list[0].'&'.$casecountquery;
	//echo $M_v_court_list[0];
	//echo $casecountquery.'&v_court='.$M_v_court_list[0];
	//echo '<br>';
	//echo $casecount;
	//echo '<br>';
	
	//$casecount = 2;
	
	//continue;
	//取得案件內容
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