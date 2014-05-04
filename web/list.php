<?php
	session_start();
	function reqSession($key, $default = '')
	{
	  return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
	}
	function req($key, $default = '')
	{
	  return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
	}
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
	
	<!-- include carousel.css -->
	<link rel="stylesheet" href="carousel/carousel.css">
	<link rel="stylesheet" href="carousel/carousel-style.css">
	
	<!-- Showup javascript -->
	<link href="css/showup.css" rel="stylesheet">
	
	<link rel="stylesheet" href="css/datatables.css">

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
    <div class="navbar navbar-default navbar-fixed-top btn-lg unselectable" role="navigation">
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

    <div class="container text-center">
      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
			<!--search bar-->
			<form class="navbar-form" role="search" name="form" action="index.php" method="get">
			  <div class="form-group">
			  <div class="row">
				<div class="col-lg-10 col-lg-offset-1">
					<div class="input-group input-group-lg">
						<input type="text" class="form-control" name="keyword" id="keyword" placeholder="輸入您想搜尋的關鍵字" value="<?php echo reqSession('keyword') ?>">
						<div class="input-group-btn">
							<li class="btn btn-primary" data-toggle="collapse" data-target="#extrafields"><i class="fa fa-plus"></i></li>
							<button type="submit" class="btn btn-danger btn-circle"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>
			  </div>
			  <div class="row collapse" id="extrafields">
				<!--for desktop & tablet-->
				<div class="hidden-xs hidden-sm col-lg-10 col-lg-offset-1">
					<div class="input-group">
						<span class="input-group-addon">裁判日期</span>
						<input type="text" class="form-control" id="date" placeholder="例：101/01/01">
						<span class="input-group-addon">裁判字號</span>
						<input type="text" class="form-control" id="no" placeholder="例：101,重上,100">
						<span class="input-group-addon">裁判案由</span>
						<input type="text" class="form-control" id="case" placeholder="例：損害賠償">
					</div>
				</div>
				<!--for mobile-->
				<div class="visible-xs visible-sm col-lg-10 col-lg-offset-1">
					<div class="input-group">
						<span class="input-group-addon">裁判日期</span>
						<input type="text" class="form-control" id="date" placeholder="例：101/01/01">
					</div>
					<div class="input-group">
						<span class="input-group-addon">裁判字號</span>
						<input type="text" class="form-control" id="no" placeholder="例：101,重上,100">
					</div>
					<div class="input-group">
						<span class="input-group-addon">裁判案由</span>
						<input type="text" class="form-control" id="case" placeholder="例：損害賠償">
					</div>
				</div>
			  </div>
			  </div>
			</form>
			<!--search bar-->
			
			<br>
			
			<div class="m-carousel m-fluid m-carousel-photos">
			  <div class="m-carousel-inner">
				<!--<div class="m-item">
				  <iframe width="100%" height="500px" frameborder="0" allowtransparency="true" scrolling="no" src="nvd3/examples/stackedAreaChart.html"></iframe>
				</div>-->
				<div class="m-item">
					<iframe width="100%" height="500px" frameborder="0" allowtransparency="true" scrolling="no" src="nvd3/examples/discreteBarChart.html"></iframe>
				</div>
				<div class="m-item">
				  <iframe width="100%" height="500px" frameborder="0" allowtransparency="true" scrolling="no" src="nvd3/examples/crossfilter.html"></iframe>
				</div>
			  </div>
			  <div class="m-carousel-controls m-carousel-hud">
				<a class="m-carousel-prev" href="#" data-slide="prev">Previous</a>
				<a class="m-carousel-next" href="#" data-slide="next">Next</a>
			  </div>
			  <div class="m-carousel-controls m-carousel-bulleted">
				<a href="#" data-slide="1" class="m-active">1</a>
				<a href="#" data-slide="2">2</a>
				<a href="#" data-slide="3">3</a>
			  </div>
			</div>
			
		<br>
		<div class="wrap well">
		<br><br>
			<table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered">
				<thead>
					<tr>
						<th>判決時間</th>
						<th>判決案號</th>
						<th>判決類型</th>
						<th>判決法院</th>
						<th>判決案由</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$list = reqSession('list');
						if($list != '')
						{
							//print_r($list);
							for($i = 0; $i < count($list); $i++)
							{
								$url = 'http://'.$_SERVER['HTTP_HOST'].'/API/index.php'.'?action=getContext'.'&'.'key='.$list[$i].'&type=json';
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
								$contenturl = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/'.'content.php'.'?key='.$key;
								echo '<tr onclick="document.location=\''.$contenturl.'\'";>';
									echo '<td>';
										echo $date;
									echo '</td>';
									echo '<td>';
										echo $key;
									echo '</td>';
									echo '<td>';
										echo $type;
									echo '</td>';
									echo '<td>';
										echo $court;
									echo '</td>';
									echo '<td>';
										echo $case;
									echo '</td>';
								echo '</tr>';
								//$context = $data['context'][0]['value'];
							}
						}
					?>
				</tbody>
			</table>
		<!--<iframe width="100%" height="600px" frameborder="0" allowtransparency="true" src="BS3/BS3/examples/pagination_full.html"></iframe>-->
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
	<!-- include carousel.js -->
	<script src="carousel/carousel.js"></script>
	<!-- construct the carousel -->
	<script>$('.m-carousel').carousel()</script>
	<script>
		$().showUp('.navbar', {upClass: 'navbar-show', downClass: 'navbar-hide'});
	</script>
	<script src="http://code.highcharts.com/highcharts.js" type="text/javascript"></script>
	<script src="http://code.highcharttable.org/master/jquery.highchartTable-min.js" type="text/javascript"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/jquery.dataTables.min.js"></script>
	<script src="js/datatables.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			$('.datatable').dataTable({
				"sPaginationType": "bs_full"
			});	
			$('.datatable').each(function(){
				var datatable = $(this);
				// SEARCH - Add the placeholder for Search and Turn this into in-line form control
				var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
				search_input.attr('placeholder', 'Search');
				search_input.addClass('form-control input-sm');
				// LENGTH - Inline-Form control
				var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
				length_sel.addClass('form-control input-sm');
			});
			
		});
		/* Add a click handler to the rows - this could be used as a callback */
		/*$('.datatable tbody tr').on('click',function(event) {
			$('.datatable tbody tr').removeClass('success');		
			$(this).addClass('success');
		});*/
		$( ".datatable tbody tr" ).hover(
		  function() {
			$(this).addClass( "success" );
		  }, function() {
			$(this).removeClass( "success" );
		  }
		);
		/* Add a click handler to the rows - this could be used as a callback */
		/*$('.datatable tbody tr').on('click',function(event) {
			var path = window.top.location.href.match( /^(http.+\/)[^\/]+$/ )[1];
			//window.top.location.href = path + "content.php";
		});*/
		$(document).ready(function() {
		  $('.highchart').highchartTable();
		});
		</script>
	</script>
  </body>
</html>