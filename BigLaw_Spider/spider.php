<?php 
  //Spider Program for Project BigLaw
  //20140418 Updated

  include_once 'common.php';
  include_once 'simple_html_dom.php';
  
  $type = getrequest('type', ''); //casecount：案件數量, caselist：案件列表, casecontext：案件內容, caseprint：友善列印
  $keyword = getrequest('keyword', '');
  $page = getrequest('page', '');
  $id = getrequest('id', '');
  $jrecno = getrequest('jrecno', '');
  $sdate = getrequest('sdate', '');
  $edate = getrequest('edate', '');
  $v_court = getrequest('v_court', '');
  $v_sys = getrequest('v_sys', '');
  $format = getrequest('format', ''); //plaintext：純文字, html：網頁
  
  $fjudEnterURL = 'http://jirs.judicial.gov.tw/FJUD/FJUDQRY01_1.aspx'; //查詢頁面
  $fjudQueryURL = 'http://jirs.judicial.gov.tw/FJUD/FJUDQRY02_1.aspx'; //案件列表
  //FJUDQRY02_1.aspx?nccharset=067EBA3C&__VIEWSTATE=dDwtMjc3OTk4NTc5Ozs%2BJGLlb0YcGTXh3AHOywBwIuJxqAk%3D&ddlPage=2&ddlPage2=1&id=&v_court=TPC+司法院－刑事補償&v_sys=M&jud_year=&jud_case=&jud_no=&jud_title=&keyword=&sdate=20110101&edate=20140228&page=2&searchkw=
  $fjudContextURL = 'http://jirs.judicial.gov.tw/FJUD/FJUDQRY03_1.aspx'; //案件內容
  //FJUDQRY03_1.aspx?id=1&v_court=TPC+司法院－刑事補償&v_sys=M&jud_year=&jud_case=&jud_no=&jud_title=&keyword=&sdate=20140101&edate=20140228&page=&searchkw=
  //http://jirs.judicial.gov.tw/FJUD/FJUDQRY03_1.aspx?id=1&v_court=TPH+%e8%87%ba%e7%81%a3%e9%ab%98%e7%ad%89%e6%b3%95%e9%99%a2&v_sys=V&jud_year=&jud_case=&jud_no=&jud_title=&keyword=%e7%ab%8a%e7%9b%9c&sdate=&edate=&page=&searchkw=%e7%ab%8a%e7%9b%9c
  $fjudPrintURL = 'http://jirs.judicial.gov.tw/FJUD/PrintFJUD03_0.aspx'; //友善列印
  //PrintFJUD03_0.aspx?jrecno=103%2c台覆%2c2%2c20140123&v_court=TPC+司法院－刑事補償&v_sys=M&jyear=103&jcase=台覆&jno=2&jdate=1030123&jcheck=
  //$refer = 'http://jirs.judicial.gov.tw/FJUD/FJUDQRY03_1.aspx?id=1&v_court=TPH 臺灣高等法院&v_sys=V&jud_year=&jud_case=&jud_no=&jud_title=&keyword=竊盜&sdate=&edate=&page=&searchkw=竊盜';
  //$url = 'http://jirs.judicial.gov.tw/FJUD/PrintFJUD03_0.aspx?jrecno=101,重上,100,20140408,2&v_court=TPH 臺灣高等法院&v_sys=V&jyear=101&jcase=重上&jno=100&jdate=1030408&jcheck=2';
  //$refer = 'http://jirs.judicial.gov.tw/FJUD/FJUDQRY03_1.aspx?id=1&v_court=TPH+%e8%87%ba%e7%81%a3%e9%ab%98%e7%ad%89%e6%b3%95%e9%99%a2&v_sys=V&jud_year=&jud_case=&jud_no=&jud_title=&keyword=%e7%ab%8a%e7%9b%9c&sdate=&edate=&page=&searchkw=%e7%ab%8a%e7%9b%9c';
  //$url = 'http://jirs.judicial.gov.tw/FJUD/PrintFJUD03_0.aspx?jrecno=101%2c%e9%87%8d%e4%b8%8a%2c100%2c20140408%2c2&v_court=TPH+%e8%87%ba%e7%81%a3%e9%ab%98%e7%ad%89%e6%b3%95%e9%99%a2&v_sys=V&jyear=101&jcase=%e9%87%8d%e4%b8%8a&jno=100&jdate=1030408&jcheck=2';
  
  //$sdata = date('Ymd', strtotime('yesterday'));//mktime(1, 2, 3, 4, 5, 2006));//strtotime('yesterday'));
  //$edate = date('Ymd', strtotime('today'));
  
  //請求參數列表
  
  //從查詢頁面查詢案件列表：FJUDQRY01_1對FJUDQRY02_1使用POST
  //Referer要使用FJUDQRY01_1
  //參數如下：
  $postdata = array(
    //'nccharset' => 'DDA9C34B', //MUST //03B96826
    //'__VIEWSTATE' => '', //dDwtMjc3OTk4NTc5Ozs%2BJGLlb0YcGTXh3AHOywBwIuJxqAk%3D
    //'ddlPage' => '',
    'id' => $id,
    'v_court' => $v_court, //MUST
    'v_sys' => $v_sys, //MUST
    'jud_year' => '',
    'sel_judword' => '常用字別', //MUST
    'jud_case' => '',
    'jud_no' => '',
    'jt' => '',
    'dy1' => '', //開始日
    'dm1' => '',
    'dd1' => '',
    'dy2' => '', //結束日
    'dm2' => '',
    'dd2' => '',
    'kw' => '',
    'keyword' => $keyword, //QUERY KEYWORD HERE
    'sdate' => $sdate,
    'edate' => $edate,
    'jud_title' => '',
    'Button' => '查詢',
    'page' => $page,
    'searchkw' => $keyword
  );
  $postdata = http_build_query($postdata);
  //echo $postdata;
  
  //從案件列表取得案件內容：FJUDQRY02_1對FJUDQRY03_1使用GET
  //Referer要使用FJUDQRY02_1
  //參數如下：
  $getdata = array(
    'id' => $id, //MUST IN CASE
    'v_court' => $v_court,  //MUST
    'v_sys' => $v_sys,  //MUST
    'jud_year' => '',
    'jud_case' => '',
    'jud_no' => '',
    'jud_title' => '',
    'keyword' => $keyword,
    'sdate' => $sdate,
    'edate' => $edate,
    'page' => $page,
    'searchkw' => $keyword
  );
  $getdata = http_build_query($getdata);
  //echo $getdata;
  
  //從案件內容取得友善列印：FJUDQRY03_1對PrintFJUD03_0使用GET
  //Referer要使用FJUDQRY03_1
  //參數如下：
  
  $caseinfo = explode(',', $jrecno);
  $jyear = isset($caseinfo[0]) ? $caseinfo[0] : '';
  $jcase = isset($caseinfo[1]) ? $caseinfo[1] : '';
  $jno = isset($caseinfo[2]) ? $caseinfo[2] : '';
  $jdate = isset($caseinfo[3]) ? $caseinfo[3] : '';
  $jcheck = isset($caseinfo[4]) ? $caseinfo[4] : '';
  
  $printdata = array(
    'jrecno' => $jrecno, //101,重上,100,20140408,2 //MUST
    'v_court' => $v_court, //MUST
    'v_sys' => $v_sys, //M //MUST
    'jyear' => $jyear, //101 //MUST
    'jcase' => $jcase, //重上 //MUST
    'jno' => $jno, //100 //MUST
    'jdate' => $jdate, //1030408 //MUST
    'jcheck' => $jcheck //2 //MUST
  );
  $printdata = http_build_query($printdata);
  
  $html = '';
  
  switch($type)
  {
    case 'casecount': case 'caselist':
      $caselisthtml = requestData($fjudQueryURL, $postdata, $fjudEnterURL, '', 'POST'); //查詢案件列表
      $html = $caselisthtml;
      break;
    case 'casecontext':
      $casecontexthtml = requestData($fjudContextURL, $postdata, $fjudQueryURL, '', 'GET'); //取得案件內容
      $html = $casecontexthtml;
      break;
    case 'caseprint':
      $caseprinthtml = requestData($fjudPrintURL, $printdata, $fjudContextURL, $getdata, 'GET'); //取得友善列印
      $html = $caseprinthtml;
      break;
    case 'test':
      echo time();
      echo '<br>';
      echoArray(explode(',', $jrecno));
      break;
  }
  
  if($type == 'casecount')
  {
	$totalcountpattern = '/本次查詢結果共(\d.*?)筆/';
	preg_match($totalcountpattern, $html, $totalcountmatches, PREG_OFFSET_CAPTURE);
	if(count($totalcountmatches) > 0)
		echo $totalcountmatches[1][0];
	else
		echo 0;
	break;
	continue;
  }
  
  $contextpattern = '/(<pre[\d\D]*?>[\d\D]*?pre>)/';
  preg_match($contextpattern, $html, $matches, PREG_OFFSET_CAPTURE);

  if($format == 'html')
    echo $html;
  else if($format == 'plaintext')
  {
    $html = str_get_html($html);
    switch($type)
    {
      case 'caselist':
        echo $html->find('title', 0)->plaintext; //標題
        echo '<br>';
        echo $html->find('span', 0)->plaintext; //查詢類型
        echo '<br>';
        //echo $html->find('table', 6)->plaintext; //案件列表 //inntertext
        $casees = $html->find('table', 6);
        for($i = 0; $i < 21; $i++)
        {
          $case = $casees->find('TR', $i)->plaintext;
          echo $case;
          echo '<br>';
        }
        break;
      case 'casecontext':
		//$info = explode(' ', $html->find('b', 0));
		//print_r($info);
		$case = array(
			'Court' => ''
		);
        echo $html->find('title', 0)->plaintext; //標題
        echo '<br>';
        echo $html->find('b', 0)->plaintext; //查詢類型
        echo '<br>';
        echo $html->find('span', 3)->plaintext; //裁判字號
        echo ' ';
        echo $html->find('span', 4)->plaintext;
        echo '<br>';
        echo $html->find('span', 5)->plaintext; //裁判日期
        echo ' ';
        echo $html->find('span', 6)->plaintext;
        echo '<br>';
        echo $html->find('span', 7)->plaintext; //裁判案由
        echo ' ';
        echo $html->find('span', 8)->plaintext;
        echo '<br>';
        echo $html->find('span', 9)->plaintext; //裁判全文
        echo '<br>';
        //echo $html->find('pre', 0);
        $context = $matches[0];
        $context = strip_tags($context[0], '<pre>');
        echo $context;
        break;
      case 'caseprint':
        echo $html->find('title', 0)->plaintext; //標題
        echo '<br>';
        echo $html->find('h3', 0)->plaintext; //查詢類型
        echo '<br>';
        echo $html->find('span', 0)->plaintext; //裁判字號
        echo ' ';
        echo $html->find('span', 1)->plaintext;
        echo '<br>';
        echo $html->find('span', 2)->plaintext; //裁判日期
        echo ' ';
        echo $html->find('span', 3)->plaintext;
        echo '<br>';
        echo $html->find('span', 4)->plaintext; //裁判案由
        echo ' ';
        echo $html->find('span', 5)->plaintext;
        echo '<br>';
        echo $html->find('span', 6)->plaintext; //裁判全文
        echo '<br>';
        //echo $html->find('pre', 0);
        $context = $matches[0];
        $context = strip_tags($context[0], '<pre>');
        echo $context;
        break;
    }
  }
    
  /*
  $tablepattern = '(<TABLE class="big" id="Table3"[\d\D]*?>[\d\D]*?TABLE>)';
  $titlepattern = '(<b>[\d\D]*?[\d\D]*?b>)';
  $infopattern = '(<table summary="排版用表格" width="95%" border="0" align="center" cellpadding="3">[\d\D]*?>[\d\D]*?<td colspan="2" align="left">)';
  
  $contextpattern = '(<pre[\d\D]*?>[\d\D]*?pre>)';
  $contextresult = preg_match($contextpattern, $html, $matches);
  */
  
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
    curl_setopt($ch, CURLOPT_HEADER, true); //enable header output
    curl_setopt($ch, CURLINFO_HEADER_OUT, true); //enable header tracking
  
    if($refererquery != '')
      curl_setopt($ch, CURLOPT_REFERER, $referer.'?'.$refererquery); //set referer url
    else
      curl_setopt($ch, CURLOPT_REFERER, $referer);
  
    $result = curl_exec($ch);
    $headerSent = curl_getinfo($ch, CURLINFO_HEADER_OUT);
    curl_close($ch);
    
    echo $headerSent.'<br/>';
    return $result;
  }
?>