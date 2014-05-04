<?php
	$url = 'http://'.$_SERVER['HTTP_HOST'].'/API/index.php'.'?action=getContext'.'&'.'key='.$_GET['key'].'&type=json';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //return raw data
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'Origin: http://jirs.judicial.gov.tw'));
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.107 Safari/537.36');
	$result = curl_exec($ch);
	curl_close($ch);
	$data = json_decode($result, true);
	$key = $data['key'];
	$court = $data['court'][0]['value'];
	$type = $data['type'][0]['value'];
	$case = $data['case'][0]['value'];
	$date = $data['date'][0]['value'];
	$context = $data['context']['value'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!--<link rel="shortcut icon" href="../../assets/ico/favicon.ico">-->

    <title>BigLaw</title>

    <!-- Bootstrap core CSS -->
    <link href="css/font-awesome.min.css" rel="stylesheet">
	
	<!-- Awesome CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/biglaw.css" rel="stylesheet">
	
	<!-- Showup javascript -->
	<link href="css/showup.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
	<!--header-->
    <!-- Fixed navbar -->
    <div id="autocollapse" class="navbar navbar-default navbar-fixed-top btn-lg unselectable" role="navigation">
      <div class="container">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">開關導航</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="index.html"><span class="navbar-brand"><i class="fa fa-bar-chart-o"></i> BigLaw</span></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.html">首頁</a></li>
			<li class="dropdown">
              <a href="#" class="dropdown-toggle collapse" data-toggle="dropdown">法律條文 <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="http://law.moj.gov.tw/LawClass/LawClassListN.aspx?TY=01000000">憲法</a></li>
                <li><a href="http://law.moj.gov.tw/LawClass/LawContent.aspx?PCODE=B0000001">民法</a></li>
                <li><a href="http://law.moj.gov.tw/LawClass/LawContent.aspx?PCODE=B0010001">民事訴訟法及相關法</a></li>
				<li><a href="http://law.moj.gov.tw/LawClass/LawContent.aspx?PCODE=C0000001">刑法</a></li>
				<li><a href="http://law.moj.gov.tw/LawClass/LawContent.aspx?PCODE=C0010001">刑事訴訟法及相關法</a></li>
				<li><a href="http://law.moj.gov.tw/LawClass/LawContent.aspx?PCODE=A0030055">行政法及行政訴訟相關法</a></li>
              </ul>
            </li>
			
			<!--for desktop & tablet-->
			<!--
			<form class="navbar-form navbar-left navbar-input-group hidden-xs" role="search">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="法律條文快速搜尋">
					<button type="submit" class="btn btn-primary">搜尋</button>
				</div>
			</form>
			-->
			<!--for mobile-->
			<!--
			<form class="navbar-form navbar-center visible-xs" role="search">
				<div class="form-group">
					<div class="input-group input-group-lg">
						<input type="text" class="form-control"  placeholder="法律條文快速搜尋">
						<div class="input-group-btn">
							<button type="submit" class="btn btn-primary">搜尋</button>
						</div>
					</div>
				</div>
			</form>
			-->
			
			</ul>
		  <ul class="nav navbar-nav navbar-right">
            <li><a href="#" id="#aboutus" class="accordion-toggle" data-toggle="collapse" data-target="#aboutusdetail" data-parent="#details">關於我們</a></li>
            <li><a href="#" id="#datasource" class="accordion-toggle" data-toggle="collapse" data-target="#datasourcedetail" data-parent="#details">資料來源</a></li>
			<li><a href="#" id="#license" class="accordion-toggle" data-toggle="collapse" data-target="#licensedetail" data-parent="#details">授權條款</a></li>
		  </ul>
		  <div id="details">
			<div class="row collapse" id="aboutusdetail">
				<div class="thumbnail pull-right">
					<p><b>&nbsp;&nbsp;&nbsp;&nbsp;開放法律判決智慧檢索與分析系統</b><br>
					&nbsp;&nbsp;&nbsp;&nbsp;這是一套使用Hadoop大量分析政府提供之法律裁判書的智慧檢索系統<br>
					使您在面對法律問題及學術探討時，能更方便快速的找到所需資訊<br>
					祝您使用的愉快！</p>
					<p>&nbsp;&nbsp;&nbsp;&nbsp;自放社會Selffnck是隸屬於MeiGic工作室的特殊專案開發團隊。</p>
				</div>
			</div>
			<div class="row collapse" id="datasourcedetail">
				<div class="thumbnail pull-right">
					本站資料來源均為政府開放資料，如下列：<br>
					<li><a href="http://jirs.judicial.gov.tw/FJUD/">司法院法學資料檢索系統</a></li>
					<li><a href="http://law.moj.gov.tw/">法務部全國法規資料庫</a></li>
					本站開發均使用OpenSource軟體，如下列：<br>
					<li><a href="http://getbootstrap.com/">Bootstrap</a></li>
					<li><a href="http://jquery.com/">jQuery</a></li>
				</div>
			</div>
			<div class="row collapse" id="licensedetail">
				<div class="thumbnail pull-right">
					<p>Copyright 2014 Selffnck</p>

					<p>Licensed under the Apache License, Version 2.0 (the "License");<br>
					you may not use this file except in compliance with the License.<br>
					You may obtain a copy of the License at</p>

					<p>http://www.apache.org/licenses/LICENSE-2.0</p>

					<p>Unless required by applicable law or agreed to in writing, software<br>
					distributed under the License is distributed on an "AS IS" BASIS,<br>
					WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.<br>
					See the License for the specific language governing permissions and<br>
					limitations under the License.</p>
				</div>
			</div>
		  </div>
        </div><!--/.nav-collapse -->
      </div>
    </div>
	<!--header-->
	<div class="container">
		<!--for desktop & tablet-->
		<div class="well col-md-3 col-lg-3 col-md-offset-8 col-lg-offset-8 hidden-xs hidden-sm" data-spy="affix" data-offset-top="50" data-offset-bottom="50">
			<i class="fa fa-dot-circle-o"></i>
			裁判法院
			<i class="fa fa-angle-double-right"></i>
			<?php echo $court; ?>
			<br>
			<i class="fa fa-dot-circle-o"></i>
			裁判類型
			<i class="fa fa-angle-double-right"></i>
			<?php echo $type; ?>
			<br>
			<i class="fa fa-dot-circle-o"></i>
			裁判字號
			<i class="fa fa-angle-double-right"></i>
			<?php echo $key; ?>
			<br>
			<i class="fa fa-dot-circle-o"></i>
			裁判日期
			<i class="fa fa-angle-double-right"></i>
			<?php echo $date; ?>
			<br>
			<i class="fa fa-dot-circle-o"></i>
			裁判案由
			<i class="fa fa-angle-double-right"></i>
			<?php echo $case; ?>
			<br>
		</div>
		<!--for mobile-->
		<div class="well col-lg-8 col-md-8 visible-xs visible-sm btn-lg">
			<i class="fa fa-dot-circle-o"></i>
			裁判法院
			<i class="fa fa-angle-double-right"></i>
			<?php echo $court; ?>
			<br>
			<i class="fa fa-dot-circle-o"></i>
			裁判類型
			<i class="fa fa-angle-double-right"></i>
			<?php echo $type; ?>
			<br>
			<i class="fa fa-dot-circle-o"></i>
			裁判字號
			<i class="fa fa-angle-double-right"></i>
			<?php echo $key; ?>
			<br>
			<i class="fa fa-dot-circle-o"></i>
			裁判日期
			<i class="fa fa-angle-double-right"></i>
			<?php echo $date; ?>
			<br>
			<i class="fa fa-dot-circle-o"></i>
			裁判案由
			<i class="fa fa-angle-double-right"></i>
			<?php echo $case; ?>
			<br>
		</div>
		<div class="well col-xs-12 col-sm-12 col-md-8 col-lg-8 pull-left">
			<h2 class="list-collapse">
				<a href="#" class="list-group-item list-group-item-info" data-toggle="collapse" data-target="#contextdetail">裁判全文</a>
			</h2>
			<div id="contextdetail" class="collapse in">
			<?php
				echo '<pre id="context" class="col-lg-offset-1">';
				echo $context;
				echo '</pre>';
			?>
			</div>
		</div>
	</div>
    </div> <!-- /container -->
	
	<!--footer-->
    <!-- Back-to-top Example -->
    <!--<a href="#" class="btn back-to-top btn-light btn-fixed-bottom"> <span class="glyphicon glyphicon-chevron-up"></span> </a>-->
    <!-- Back-to-top Example -->
    <!--<a href="#" style="right: 70px;" class="btn back-to-top btn-dark btn-fixed-bottom"> <span class="glyphicon glyphicon-chevron-up"></span> </a>-->
	<!-- Back-to-top buttons -->
	<a id="back-to-top" href="#" class="btn btn-circle btn-success back-to-top" role="button">
	<i class="fa fa-chevron-up fa-3x"></i>
	</a>
	<footer class="text-center">
      <p>&copy; Selffnck@MeiGic 2014</p>
    </footer>
	<!--footer-->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="js/biglaw.js"></script>
	<script src="js/showup.js"></script>
	<script src="js/affix.js"></script>
	<script>
		$().showUp('.navbar', {upClass: 'navbar-show', downClass: 'navbar-hide'});
	</script>
  </body>
</html>