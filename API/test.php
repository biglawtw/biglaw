<!DOCTYPE>
<html>
<head>
<title>Test</title>
<script type="text/javascript" src="jquery-1.11.0.min.js"></script>
<script type="text/javascript">
	
	$(function(){
		$("#btn").click(function(){
			$.post("index.php" , {
				"action": "getContext",
				"key": $("#search").val(),
				"type": "json"
			} , function(cb){
				var rb = $.parseJSON(cb);
				$("#result").text(rb.context.value);
			})
		});

		$("#btn2").click(function(){
			$.post("index.php" , {
				"action": "keyword",
				"keyword": $("#keyword").val(),
				"type": "json"
			} , function(cb){
				var rb = $.parseJSON(cb);
				$("#result").text(rb);
			})
		});
	});


</script>
</head>
<body>
<input id="search" type="text" /><input type="button" id="btn" value="query" />
<br />
<input id="keyword" type="text" /><input type="button" id="btn2" value="search" />
<pre>
<div id="result"></div>
</pre>
</body>
</html>