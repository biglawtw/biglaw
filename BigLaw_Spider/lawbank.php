<?php
//http://fyjud.lawbank.com.tw/list2.aspx
///listcontent4.aspx
//?courtFullName=SLDM&v_court=&v_sys=&jud_year=&jud_case=&jud_no=&jud_title=&jud_jmain=&keyword=&sdate=20140103&edate=20140303&file=&page=&id=&searchkw=&jcatagory=0&switchFrom=1&issimple=-1
$listcontent4 = 'http://fyjud.lawbank.com.tw/listcontent4.aspx';
$query = 'courtFullName=SLDM&v_court=&v_sys=&jud_year=&jud_case=&jud_no=&jud_title=&jud_jmain=&keyword=&sdate=20140103&edate=20140303&file=&page=&id=&searchkw=&jcatagory=0&switchFrom=1&issimple=-1';
$referer = 'http://fyjud.lawbank.com.tw/list2.aspx';
$fullurl = $listcontent4.'?'.$query;
echo requestData($fullurl, '', '', '', 'GET');

function requestData($url, $urlquery, $referer, $refererquery, $type)
  {
    $ch = curl_init();
    
    if($type == 'GET')
    {
      if($urlquery != '')
        curl_setopt($ch, CURLOPT_URL, $url.'?'.$urlquery);
      else
        curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    }
    else if($type=='POST')
    {
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $urlquery);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    }
  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //return raw data
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'Origin: http://jirs.judicial.gov.tw'));
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.107 Safari/537.36');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //tracking redirecting
    //curl_setopt($ch, CURLOPT_HEADER, true); //enable header output
	//curl_setopt($ch, CURLINFO_HEADER_OUT, true); //enable header tracking
	$cookie = '';
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
	if($referer != '')
    if($refererquery != '')
      curl_setopt($ch, CURLOPT_REFERER, $referer.'?'.$refererquery); //set referer url
    else
      curl_setopt($ch, CURLOPT_REFERER, $referer);
  
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }
?>