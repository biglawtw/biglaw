var currentquery;
var lastquery;
var currentid;
var lastid;
var currentpage;
function loadandparseifnotnull(id, context, parsebool) {
	if(context && context != "") {
		$("#" + id).html(context);
		if(parsebool)
			parse(id);
		$("#" + id).parent().show();
	} else {
		$("#" + id).html('');
		$("#" + id).parent().hide();
	}
}
var querytitle;
var casetitle;
var title;
function loadelse() {
	//console.log("loadelse");
    
    if(lastpara && currentpara.toString() != lastpara.toString()) {
        hideall();
        $("html, body").scrollTop(0);
    }
        
	setupsearchform();
	document.title = projectname;

	var success = true;
	if(currentpara[0] == "result" && currentpara.length > 1 && currentpara[1] && currentpara[2]) {
		if(currentpara[1] == "id") {
			if(currentpara[2] != lastid || lastpara[0] != "result") {
				setelseloadingenable(true, function() {
					currentid = currentpara[2];
					lastid = currentpara[2];

					$("#Word").html('');

					getcase(currentid, function(bool) {
						if(bool) {
							$("#case").find(".collapse").addClass("in").removeAttr("style"); //restore case collapse in status

							setelseloadingenable(false, function() {
								showdetail();
							});
						} else {
							success = false;
						}
						casetitle = $("#Word").html();
						title = casetitle;
						settitle();

						if(!success) {
							setelseloadingenable(false, function() {
								showempty();
							});
						}
					});
				});
			} else {
				showdetail();
			}
			casetitle = $("#Word").html();
			title = casetitle;
		} else if(currentpara[1] == "q") {
			builddaterange();
			if(currentpara[2] != lastquery || lastpara[0] != "result" || currentdaterange != lastdaterange) {
				setelseloadingenable(true, function() {
					var footable = $('#resulttable').data('footable');
					currentquery = encodeURIComponent(decodeURIComponent(currentpara[2])); //let all browser works
					lastquery = currentpara[2];

					$("#keyword").val(decodeURIComponent(currentpara[2]));
                    keywordtoform();
                    
					footable.removeRow($('#resulttable').find("tbody").find("tr")); //clean up footable

					isdataloadpaused = true;
					checkdataloadingstatus(false);

					$('#filterselect').select2("val", "");

					destoryCharts();
					
					currentpage = 1;
					
					getsearch(currentquery, function(bool) {
						if(bool) {
							setelseloadingenable(false, function() {
								showgeneral();
								
								$(window).trigger('resize'); //make footable hide coloum works
								
								updatedata();
							});
						} else {
							success = false;
						}
						querytitle = $("#keyword").val();
						title = querytitle;
						settitle();

						if(!success) {
							setelseloadingenable(false, function() {
								showempty();
							});
						}
					});
				});
			} else {
				showgeneral();
			}
			querytitle = $("#keyword").val();
			title = querytitle;
		} else {
			success = false;
		}
	} else {
		success = false;
	}
	settitle();

	if(!success) {
		showempty();
	}
}
function settitle() {
    if(title && title != "")
		document.title = title + ' - ' + projectname;
	else
		document.title = projectname;
}
function unloadpage() {
	//console.log("unloadpage");
	isdataloadpaused = true;
	checkdataloadingstatus(false);
	issearchformloaded = false;
}
function getcase(id, onCompleteEvent) {
	$.getJSON(serverURL + "api/getcase/?id=" + id, function(data) {
		//console.log(data);

		$("#Location").html(data.Location);
		$("#Type").html(data.Type);
		$("#Word").html(data.Caseyear + "," + data.Character + "," + data.Number);
		$("#Judgedate").html(data.Judgedate);
		$("#Judgereason").html(data.Judgereason);

		var context = "";
		if(data.Judge && data.Judge != "null") {
			var pre = data.Location.replace(/\-/g, '') + data.Judge;
			var post = data.Caseyear + "年度" + data.Character + "字" + "第" + data.Number + "號";
			/*var spacecount = 8;
			var space = "";
			for(var i = 0; i < spacecount; i++)
				space += " ";*/
			context += pre + "<br>" + post + "<br>";
		}
		if(data.Premain && data.Premain != "null")
			context += data.Premain;
		if(data.Main && data.Main != "null")
			context += data.Main;
		loadandparseifnotnull("casecontext", context, true);
		loadandparseifnotnull("casefact1", data.Fact1, false);
		loadandparseifnotnull("casefact1word", data.Fact1Word, true);
		loadandparseifnotnull("casefact2", data.Fact2, false);
		loadandparseifnotnull("casefact2word", data.Fact2Word, true);
		loadandparseifnotnull("casetail", data.Tail, true);
		
		buildcasechapter();

		if(onCompleteEvent)
			onCompleteEvent(true);
	});
}
function buildcasechapter() {
	$("#casechapter").html('');
	appendcasechapterifexist("casecontext", "開 頭");
	appendcasechapterifexist("casefact1");
	appendcasechapterifexist("casefact2");
	appendcasechapterifexist("casetail", "結 尾");
}
function appendcasechapterifexist(id, text) {
	if($("#" + id).length > 0 && $("#" + id)[0].innerHTML != "") {
		var hash = currenthash.split('@')[0];
		if(!text || (text && text == ""))
			text = $("#" + id).html();
		
		$("#casechapter").append('<span id="' + id + 'link">\
								 	<strong><i class="fa fa-link"></i><a href="#' + hash + "@" + id + '"> ' + text + '</a>\
								 </strong></span><br>');
		$("#" + id + "link").on("click", function(e) {
			e.preventDefault();
			var scrolltop = $("#" + id).offset().top - scrolltopoffset;
			$("html, body").animate({ scrollTop: scrolltop }, animatePeriod);
		});
	}
}
var isdataloadpaused = true;
var isdataloadfinished = false;
function checkdataloadingstatus(resume) {
	$("#switchdataloading").children("i").removeClass("fa-pause").removeClass("fa-play");
	if(isdataloadpaused) {
		$("#switchdataloading").children("i").addClass("fa-play");
		$("#dataloading").hide();
	} else {
		$("#switchdataloading").children("i").addClass("fa-pause");
		$("#dataloading").show();
		if(resume) {
			var count = $("#resulttable").find("tbody").find("tr:not(.footable-filtered)").length;
			currentpage++;
			getsearch(currentquery, function() {
				updatedata();
			});
		}
	}
}
function escapeHTML(unsafe) {
	return unsafe
		.replace(/(<([^>]+)\.\.\.)/ig, "..."); //replace not closure html tag
		//.replace(/(<([^>]+)>)/ig,"")
		/*.replace(/&/g, "&amp;")
		.replace(/</g, "&lt;")
		.replace(/>/g, "&gt;")
		.replace(/"/g, "&quot;")
		.replace(/'/g, "&#039;");*/
}
var currentdaterange;
var lastdaterange;
function builddaterange() {
	//build daterange
	lastdaterange = currentdaterange;
	currentdaterange = "";
	var startdate, enddate;
	for(var i = 0; i < currentpara.length; i++) {
		if(currentpara[i].indexOf("startdate") != -1)
			startdate = currentpara[i].split("=")[1];
		else if(currentpara[i].indexOf("enddate") != -1)
			enddate = currentpara[i].split("=")[1];
	}
	if(startdate)
		currentdaterange += '&fromdate=' + startdate;
	if(enddate)
		currentdaterange += '&todate=' + enddate;
}
function getsearch(query, onCompleteEvent) {
	//console.log(currentpage);
	$.getJSON(serverURL + "api/search/?keyword=" + query + "&page=" + currentpage + currentdaterange, function(data) {
		if(data.list && data.list.length > 0) {
			var list = data.list;
			var totalcount = data.count;
			$("#casecount").html(totalcount);
			var newdata = [];
			$(list).each(function(key, value) {
				//console.log(value.caselaw);
				var caselaw = "無";
				if(value.caselaw && value.caselaw != "null") {
					caselaw = [];
					for(var i = 0; i < value.caselaw.length; i++) {
						caselaw.push(value.caselaw[i].lawname);
					}
					caselaw = caselaw.join("、");
					//console.log(caselaw);
				}
				var summary = escapeHTML(value.casesummary);
				//console.log(summary);
				var newrecord = '<tr>\
									<td>' + value.caseid + '</td>\
									<td data-value="' + value.casedate + '">' + value.casedate.substr(0, 4) + "/" + value.casedate.substr(4, 2) + "/" + value.casedate.substr(6, 2) + '</td>\
									<td>' + value.caseword + '</td>\
									<td>' + value.casereason + '</td>\
									<td>' + value.casecourt + '</td>\
									<td>' + caselaw + '</td>\
									<td>' + summary + '</td>\
									<td><a type="button" class="btn btn-success" href="#result&id&' + value.caseid + '">閱讀裁判書</a></td>\
								</tr>';
				newdata.push(newrecord);
			});
			$('#resulttable').find("tbody").append(newdata).trigger('footable_redraw'); //append ajax rows to table
			
			if(onCompleteEvent)
				onCompleteEvent(true);

			var totalpages = Math.ceil(totalcount / caseperrequest);
			$("#dataloadpercent").html(((currentpage / totalpages) * 100).toFixed(2));
			if(currentpage < totalpages) {
				isdataloadfinished = false;
				//console.log(currentpage+1);
				$("#dataloadingdiv").show();
				$("#dataloadeddiv").hide();
				if(!isdataloadpaused) {
					currentpage++;
					setTimeout(getsearch(query, function() {
						updatedata();
					}), requestPeriod);
				}
			} else {
				isdataloadfinished = true;
				$("#dataloadingdiv").hide();
				$("#dataloadeddiv").show();
			}
		} else {
			if(onCompleteEvent)
				onCompleteEvent(false);
		}
	});
}
function updatedata() {
	resultcount(); //count the number of current table
	buildfilterdata();
	buildcasecourtpiechart();
	buildjudgetimesplinechart();
    //resizecharts();
	//$("#paging").parent().removeAttr("style"); //show resulttable paging
}
function buildfilterdata() {
	var reasondata = {};
	var courtdata = {};
	var lawdata = {};
	var trs = $("#resulttable").find("tbody").find("tr").each(function(key, value) {
		var tds = $(this).find("td");
		if(tds.length >= 5) {
			var casereason = $(this).find("td")[3].innerHTML;
			var courtname = $(this).find("td")[4].innerHTML;
			var lawname = $(this).find("td")[5].innerHTML;
			reasondata[casereason] = reasondata[casereason]+1 || 1; // Increment counter for each value
			courtdata[courtname] = courtdata[courtname]+1 || 1; // Increment counter for each value
			lawdata[lawname] = lawdata[lawname]+1 || 1; // Increment counter for each value
		}
	});
	reasondata = $.map(reasondata, function(value, index) {
		return [index];
	});
	courtdata = $.map(courtdata, function(value, index) {
		return [index];
	});
	lawdata = $.map(lawdata, function(value, index) {
		return [index];
	});
	var tempdata = [];
	var filterdata = [];
	tempdata = tempdata.concat(reasondata, courtdata, lawdata);
	for(var i = 0; i < tempdata.length; i++) {
		filterdata.push({id:tempdata[i], text:tempdata[i]});
	}
	var data = "";
	if($("#filterselect").select2)
		data = $("#filterselect").select2("data");
	$("#filterselect").select2({
		allowClear: true,
		createSearchChoice:function(term, data) {
			if ($(data).filter(function() {
				return this.text.localeCompare(term)===0; }).length===0) {
				return {id:term, text:term};
			}
		},
		multiple: true,
		data: filterdata
	});
	$("#filterselect").select2("data", data, true);
}
function updatedcasefontsize(offset) {
	var postfix = 'px';
	var fontsizestring = $('#case').css('font-size');
	fontsizestring = fontsizestring.substr(0, fontsizestring.length - postfix.length);
	var fontsize = +(fontsizestring) + offset;
	fontsize = Math.clamp(fontsize, 0, 25);
	$('#case').css('font-size', fontsize + postfix);
}
function setelseloadingenable(bool, onCompleteEvent) {
	//console.log("setelseloadingenable");
	if(bool) {
		$( "#elseloading" ).removeClass("hidden"); //show loading animation
		$( "#elseloading" ).animate({opacity: 1}, animatePeriod, function() {
			if(onCompleteEvent)
				onCompleteEvent();
		});
	} else {
		$( "#elseloading" ).animate({opacity: 0}, animatePeriod, function() {
			$( "#elseloading" ).addClass("hidden"); //hide loading animation
			if(onCompleteEvent)
				onCompleteEvent();
		});
	}
}
function setenable(id, bool) {
    //console.log("setenable:" + id + " " + bool);
	if(bool) {
		$( '#' + id ).removeClass('hidden');
		$( '#' + id ).show( "blind", { direction: "left" }, "fast");
	} else {
		$( '#' + id ).hide( "blind", { direction: "left" }, "fast", function() {
			$( '#' + id ).addClass('hidden');
		});
	}
}
function hideall() {
	/*setenable("search", false);
	setenable("general", false);
	setenable("detail", false);
	setenable("empty", false);*/
    $("#search").hide();
    $("#general").hide();
    $("#detail").hide();
    $("#empty").hide();
}
function showempty() {
	setenable("search", false);
	setenable("general", false);
	setenable("detail", false);
	setenable("empty", true);
}
function showgeneral() {
	setenable("search", true);
	setenable("general", true);
	setenable("detail", false);
	setenable("empty", false);
    scrolltophashid();
}
function showdetail() {
	setenable("search", false);
	setenable("general", false);
	setenable("detail", true);
	setenable("empty", false);
    scrolltophashid();
}
function parse(id) {
	var input = document.getElementById(id).innerHTML;
	//input = input.replace(/</g, "&lt;").replace(/>/g, "&gt;");
	input = input.replace(/\n/g, "<br>");
	document.getElementById(id).innerHTML = input;
	LER.parse(document.getElementById(id), function() {
		//console.log("Complete!");
	});
}
//parse();
function resultcount() {
	var count = $("#resulttable").find("tbody").find("tr:not(.footable-filtered)").length;
	$("#resultcount").html(count);
	//console.log(count);
}

var caselawpiechart, caselawpiechartdata = [];
var casecourtpiechart, casecourtpiechartdata = [];
var judgetimesplinechart, judgetimesplinechartdata = [];
function destoryCharts() {
	clearcharts();
	clearchartsdata();
}
function clearcharts() {
	caselawpiechart = undefined;
	casecourtpiechart = undefined;
	judgetimesplinechart = undefined;
}
function clearchartsdata() {
	caselawpiechartdata = undefined;
	casecourtpiechartdata = undefined;
	judgetimesplinechartdata = undefined;
}
function resizecharts() {
	if(casecourtpiechart) {
		casecourtpiechart = new Highcharts.Chart(casecourtpiechart.options);
		//casecourtpiechart.setSize($("#chartstabscontent").width(), $("#chartstabscontent").height()); //trigger legend to fit the div
		casecourtpiechart.reflow(); //trigger charts to fit the div
	}
	if(judgetimesplinechart) {
		//judgetimesplinechart.setSize($("#chartstabscontent").width(), $("#chartstabscontent").height());
		judgetimesplinechart.reflow();
	}
}
/*
$(function () {
	// Build the chart
	caselawpiechart = new Highcharts.Chart({
			chart: {
				renderTo:'caselawpiecontainer',
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: {
				text: '裁判書之相關法律比例圓餅圖'
			},
			subtitle: {
				text: '來源：司法院裁判書查詢系統',
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: false
					},
					showInLegend: true
				}
			},
			series: [{
				type: 'pie',
				name: '比例',
				data: [
					['貪污治罪條例',   45.0],
					['刑事訴訟法',       30.0],
					['刑事補償法',    25.0],
				]
			}]
		});
});*/
function buildcasecourtpiechart() {
	//build chart data
	var courtcount = {};
	var trs = $("#resulttable").find("tbody").find("tr").not(".footable-filtered").each(function(key, value) {
		var tds = $(this).find("td");
		if(tds.length >= 5) {
			var courtname = $(this).find("td")[4].innerHTML;
			courtcount[courtname] = courtcount[courtname]+1 || 1; // Increment counter for each value
		}
	});
	courtcount = $.map(courtcount, function(value, index) {
		return [[index, +(value)]];
	});
	//sort the data
	courtcount.sort(function(a, b) {
		return a[1] - b[1];
	});
	//console.log(courtcount);
	casecourtpiechartdata = courtcount;
	// Build the chart
	if(typeof casecourtpiechart == 'undefined') {
		//console.log(casecourtpiechartdata);
		casecourtpiechart = new Highcharts.Chart({
			chart: {
				renderTo:'casecourtpiecontainer',
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: {
				text: '裁判書之裁判法院比例圓餅圖'
			},
			credits: {
				enabled: false
			},
			subtitle: {
				text: '來源：司法院裁判書查詢系統',
			},
			tooltip: {
				headerFormat: '',
				pointFormat: '{point.name}<br>{point.percentage:.1f}%<br>件數: {point.y}'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: false,
						distance: -50,
						format: '{point.percentage:.1f} %',
						color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        style: {
                            textShadow: '0 0 3px black, 0 0 3px black'
                        },
					},
					showInLegend: true
				}
			},
			legend: {
				enabled: true
			},
			series: [{
				type: 'pie',
				name: '比例',
				data: casecourtpiechartdata/*[
					['臺灣臺北地方法院',       26],
					['臺灣士林地方法院',    24],
					['臺灣臺東地方法院',     30],
					['臺灣高等法院',     20]
				]*/
			}]
		});
	} else {
		casecourtpiechart.series[0].setData(casecourtpiechartdata);
		casecourtpiechart.redraw();
	}
}
function buildjudgetimesplinechart() {
	//set highchart timezone
	Highcharts.setOptions({
		global: {
			timezoneOffset: 8 * 60,
		}
	});
	
	//build chart data
	var casetimecount = {};
	$("#resulttable").find("tbody").find("tr").not(".footable-filtered").each(function(key, value) {
		var tds = $(this).find("td");
		if(tds.length >= 5) {
			var courtname = $(this).find("td")[4].innerHTML;
			var casetime = $(this).find("td")[1].getAttribute("data-value");
			casetime = casetime.toString();
			casetime = new Date(casetime.substr(0, 4), casetime.substr(4, 2), 1).getTime();
			if(!casetimecount[courtname])
				casetimecount[courtname] = {};
			casetimecount[courtname][casetime] = casetimecount[courtname][casetime]+1 || 1; // Increment counter for each value
		}
	});
	casetimecount = $.map(casetimecount, function(value, index) {
		value = $.map(value, function(val, ind) {
			return [[+(ind), +(val)]];
		});
		return [{name:index, data:value}];
	});
	//sort the data
	$(casetimecount).each(function(key, value) {
		value.data.sort(function(a, b) {
			return a[0] - b[0];
		});
	});
	//console.log(casetimecount);
	judgetimesplinechartdata = casetimecount;
	
	if(typeof judgetimesplinechart == 'undefined') {
		judgetimesplinechart = new Highcharts.Chart({
			chart: {
				type: 'column',
				renderTo:'judgetimesplinecontainer',
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			credits: {
				enabled: false
			},
			title: {
				text: '裁判書之裁判法院對時間的累計線圖',
			},
			subtitle: {
				text: '來源：司法院裁判書查詢系統',
			},
			/*plotOptions: {
				series:{
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						format: '{point.y}件'
					}
				}
			},*/
			plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: false,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        style: {
                            textShadow: '0 0 3px black, 0 0 3px black'
                        },
						format: '{point.y}件'
                    }
                }
            },
			xAxis: {
				type: 'datetime',
				dateTimeLabelFormats: {
					month: '%b %Y'
				},
				title: {
					text: '日期'
				}
			},
			yAxis: {
				title: {
					text: '數量(件)'
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}],
				min: 0,
				allowDecimals: false
			},
			tooltip: {
				headerFormat: '{point.x:%b %Y}<br>',
				//pointFormat: '件數: {point.y}'
				valueSuffix: '件'
			},
			legend: {
				enabled: true
			},
			series: judgetimesplinechartdata
			/*[{
				name: '臺灣臺北地方法院',
				data: [[new Date(2014, 10, 1).getTime(), 2],
					  [new Date(2014, 11, 1).getTime(), 5],
					  [new Date(2014, 12, 1).getTime(), 10]]
			}, {
				name: '臺灣臺東地方法院',
				data: [[new Date(2014, 10, 1).getTime(), 2],
					  [new Date(2014, 11, 1).getTime(), 5],
					  [new Date(2014, 12, 1).getTime(), 10]]
			}, {
				name: '臺灣高等法院',
				data: [[new Date(2014, 10, 1).getTime(), 2],
					  [new Date(2014, 11, 1).getTime(), 5],
					  [new Date(2014, 12, 1).getTime(), 10]]
			}, {
				name: '臺灣士林地方法院',
				data: [[new Date(2014, 10, 1).getTime(), 2],
					  [new Date(2014, 11, 1).getTime(), 5],
					  [new Date(2014, 12, 1).getTime(), 10]]
			}]*/
		});
	} else {
		//judgetimesplinechart.series[0].setData(judgetimesplinechartdata);
		//judgetimesplinechart.redraw();
		for(var i = 0; i < judgetimesplinechartdata.length; i++) {
			for(var j = 0; j < judgetimesplinechart.series.length; j++) {
				if(judgetimesplinechart.series[j].name == judgetimesplinechartdata[i].name) {
					judgetimesplinechart.series[j].setData(judgetimesplinechartdata[i].data);
					break;
				}
			}
		}
		judgetimesplinechart.redraw();
	}
}