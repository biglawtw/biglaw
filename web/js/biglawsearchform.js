var issearchformloaded = false;
function setupsearchform() {
	$(document).ready(function () {
        if(issearchformloaded) return;
        //console.log(issearchformloaded);
        
		//search form setup
		$( "#searchform" ).submit(function( event ) {
			event.preventDefault();
            //formtokeyword();
            //$("#keyword").val(buildfinalkeyword());
			if($("#keyword").val() != "") {
				var url = "#result" + "&q&" + $("#keyword").val();
				if($("#startdate").val() != "")
					url += "&startdate=" + $("#startdate").val();
				if($("#enddate").val() != "")
					url += "&enddate=" + $("#enddate").val();
				location.href = url;
                $("#keyword").val(manualkeyword.join(" "));
            }
		});
        
        $("#searchbtn").on("click", function() {
            formtokeyword();
			$("#keyword").val(buildfinalkeyword());
			if(($("#startdate").val() != "" || $("#enddate").val() != "") && $("#keyword").val() == 0)
	 			$("#keyword").val(" ");
        });
		
		$("#courtname").empty().append('<option></option>\
										<OPTION value="TPC 司法院－刑事補償">司法院－刑事補償</OPTION>\
										<OPTION value="TPU 司法院－訴願決定">司法院－訴願決定</OPTION>\
										<OPTION value="TPJ 司法院職務法庭">司法院職務法庭</OPTION>\
										<OPTION value="TPS 最高法院">最高法院</OPTION>\
										<OPTION value="TPA 最高行政法院">最高行政法院</OPTION>\
										<OPTION value="TPP 公務員懲戒委員會">公務員懲戒委員會</OPTION>\
										<OPTION value="TPH 臺灣高等法院">臺灣高等法院</OPTION>\
										<OPTION value="TPH 臺灣高等法院－訴願決定">臺灣高等法院－訴願決定</OPTION>\
										<OPTION value="TPB 臺北高等行政法院">臺北高等行政法院</OPTION>\
										<OPTION value="TCB 臺中高等行政法院">臺中高等行政法院</OPTION>\
										<OPTION value="KSB 高雄高等行政法院">高雄高等行政法院</OPTION>\
										<OPTION value="IPC 智慧財產法院">智慧財產法院</OPTION>\
										<OPTION value="TCH 臺灣高等法院 臺中分院">臺灣高等法院-臺中分院</OPTION>\
										<OPTION value="TNH 臺灣高等法院 臺南分院">臺灣高等法院-臺南分院</OPTION>\
										<OPTION value="KSH 臺灣高等法院 高雄分院">臺灣高等法院-高雄分院</OPTION>\
										<OPTION value="HLH 臺灣高等法院 花蓮分院">臺灣高等法院-花蓮分院</OPTION>\
										<OPTION value="TPD 臺灣臺北地方法院">臺灣臺北地方法院</OPTION>\
										<OPTION value="SLD 臺灣士林地方法院">臺灣士林地方法院</OPTION>\
										<OPTION value="PCD 臺灣新北地方法院">臺灣新北地方法院</OPTION>\
										<OPTION value="ILD 臺灣宜蘭地方法院">臺灣宜蘭地方法院</OPTION>\
										<OPTION value="KLD 臺灣基隆地方法院">臺灣基隆地方法院</OPTION>\
										<OPTION value="TYD 臺灣桃園地方法院">臺灣桃園地方法院</OPTION>\
										<OPTION value="SCD 臺灣新竹地方法院">臺灣新竹地方法院</OPTION>\
										<OPTION value="MLD 臺灣苗栗地方法院">臺灣苗栗地方法院</OPTION>\
										<OPTION value="TCD 臺灣臺中地方法院">臺灣臺中地方法院</OPTION>\
										<OPTION value="CHD 臺灣彰化地方法院">臺灣彰化地方法院</OPTION>\
										<OPTION value="NTD 臺灣南投地方法院">臺灣南投地方法院</OPTION>\
										<OPTION value="ULD 臺灣雲林地方法院">臺灣雲林地方法院</OPTION>\
										<OPTION value="CYD 臺灣嘉義地方法院">臺灣嘉義地方法院</OPTION>\
										<OPTION value="TND 臺灣臺南地方法院">臺灣臺南地方法院</OPTION>\
										<OPTION value="KSD 臺灣高雄地方法院">臺灣高雄地方法院</OPTION>\
										<OPTION value="HLD 臺灣花蓮地方法院">臺灣花蓮地方法院</OPTION>\
										<OPTION value="TTD 臺灣臺東地方法院">臺灣臺東地方法院</OPTION>\
										<OPTION value="PTD 臺灣屏東地方法院">臺灣屏東地方法院</OPTION>\
										<OPTION value="PHD 臺灣澎湖地方法院">臺灣澎湖地方法院</OPTION>\
										<OPTION value="KMH 福建高等法院金門分院">福建高等法院金門分院</OPTION>\
										<OPTION value="KMD 福建金門地方法院">福建金門地方法院</OPTION>\
										<OPTION value="LCD 福建連江地方法院">福建連江地方法院</OPTION>\
										<OPTION value="KSY 臺灣高雄少年及家事法院">臺灣高雄少年及家事法院</OPTION>');
		$("#courtname").select2({
			allowClear: true
		});
		$("#courtname").on("change", function(e) {
			//SelWord();
			checkcaseclass();
            //formtokeyword();
            if($(this).select2('val') == null || typeof $(this).select2('val') == 'undefined' || $(this).select2('val') == "") {
                sel_class = 'N';
                updatecaseclass();
            }
		});
		
		$('#casesys').find('label').click(function() {
			//$('#casesys').find("input").addClass('active').not(this).removeClass('active');
			sel_class = $(this).find("input").val();
			//console.log(sel_class);
			updatecaseclass();
		});
        /*
        $("#casesys").on("change", function(e) {
            formtokeyword();
        });
                         
        $("#caseyear").on("change", function(e) {
            formtokeyword();
        });
                         
        $("#caseword").on("change", function(e) {
            formtokeyword();
        });
                        
        $("#casenum").on("change", function(e) {
            formtokeyword();
        });
        
        $("#casereason").on("change", function(e) {
            formtokeyword();
        });
        */

		$('#startdate').datepicker({
			format: 'yyyy-mm-dd',
			autoclose: true
		});
        /*
        $("#startdate").on("change", function(e) {
            formtokeyword();
        });
        */

		$('#enddate').datepicker({
			format: 'yyyy-mm-dd',
			autoclose: true
		});
        /*
        $("#enddate").on("change", function(e) {
            formtokeyword();
        });
        */
		
        /*
		$("#keyword").on("change", function() {
			keywordtoform();
		});
        */
		
        sel_class = 'N';
		updatecaseclass();
		issearchformloaded = true;
	});
}
function addcolmappedkeyword(colname, coldata) {
    if(coldata)
        return colname + ":" + coldata;
    else 
        return "";
}
function gettypebyselclass() {
    var type = "";
    switch(sel_class) {
        case 'N':
			type = "";
        break;
        case 'M':
            type = "刑事"; 
        break;
        case 'V':
            type = "民事"; 
        break;
        case 'A':
            type = "行政"; 
        break;
        case 'P':
            type = "公懲"; 
        break;
    }
    return type;
}
function getselclassbytype(type) {
    var selclass = "";
    switch(type) {
		case '':
			selclass = 'N';
		break;
        case '刑事':
			selclass = 'M';
        break;
        case '民事':
            selclass = 'V';
        break;
        case '行政':
            selclass = 'A';
        break;
        case '公懲':
            selclass = 'P';
        break;
    }
    return selclass;
}
function removeeleifempty(id) {
	for(var i = 0; i < id.length; i++) {
		if(typeof id[i] == undefined || (id[i].length <= 0)) {
			id.splice(i, 1);
			i--;
		}
	}
}
var keyword = [];
function formtokeyword() {
    getmanualkeyword();
    
    keyword = [];
    var Location, Caseyear, Character, Number, Judgereason, Type, Judgedatestart, Judgedateend;
    Location = $("#courtname").select2('data') ? $("#courtname").select2('data').text : "";
    keyword.push(addcolmappedkeyword("Location", Location));
	Type = gettypebyselclass();
    keyword.push(addcolmappedkeyword("Type", Type));
    Caseyear = $("#caseyear").val();
    keyword.push(addcolmappedkeyword("Caseyear", Caseyear));
    Character = $("#caseword").val();
    keyword.push(addcolmappedkeyword("Character", Character));
    Number = $("#casenum").val();
    keyword.push(addcolmappedkeyword("Number", Number));
    Judgereason = $("#casereason").val();
    keyword.push(addcolmappedkeyword("Judgereason", Judgereason));
    /*Judgedatestart = $("#startdate").val();
    keyword.push(addcolmappedkeyword("Judgedatestart", Judgedatestart));
	Judgedateend = $("#enddate").val();
	keyword.push(addcolmappedkeyword("Judgedateend", Judgedateend));*/
	removeeleifempty(keyword);
    //console.log(keyword);
	
	/*var finalkeyword = [];
	finalkeyword = finalkeyword.concat(manualkeyword, keyword);
	removeeleifempty(finalkeyword);*/
	//console.log(finalkeyword);
    //$("#keyword").val(manualkeyword.join(" "));
}
function setmatchoption(id, text) {
	var options = $("#" + id)[0].options;
	for(var i = 0; i < options.length; i++) {
		if(options[i].innerHTML == text) {
			$("#" + id)[0].selectedIndex = i;
			break;
		}
	}
}
function keywordtoform() {
    getmanualkeyword();
    
	var keywordobject = {};
    var keyword = $("#keyword").val();
	var keywordpara = keyword.split(' ');
	$(keywordpara).each(function(key, value) {
		var apara = value.split(':');
		keywordobject[apara[0]] = apara[1];
	});
	//var Location, Caseyear, Character, Number, Judgereason, Type, Judgedate;
	setmatchoption("courtname", keywordobject["Location"]);
	$("#courtname").change();
    sel_class = getselclassbytype(typeof keywordobject["Type"] == 'undefined' ? "" : keywordobject["Type"]);
	setbtngroupvalue("casesys", sel_class);
	$("#casesys").change();
	$("#caseyear").val(keywordobject["Caseyear"]).change();
	$("#caseword").val(keywordobject["Character"]).change();
	$("#casenum").val(keywordobject["Number"]).change();
	$("#casereason").val(keywordobject["Judgereason"]).change();
	
	var startdate, enddate;
	for(var i = 0; i < currentpara.length; i++) {
		if(currentpara[i].indexOf("startdate") != -1)
			startdate = currentpara[i].split("=")[1];
		else if(currentpara[i].indexOf("enddate") != -1)
			enddate = currentpara[i].split("=")[1];
	}
	$("#startdate").val(startdate).change();
	$("#enddate").val(enddate).change();
	//$("#caseyear").change();
	//console.log(keywordobject["Type"]);
    $("#keyword").val(manualkeyword.join(" "));
}
var manualkeyword = [];
function getmanualkeyword() {
    manualkeyword = [];
    var originalkeyword = $("#keyword").val();
	var originalkeywordparas = originalkeyword.split(" ");
	for(var i = 0; i < originalkeywordparas.length; i++) {
		if(originalkeywordparas[i].indexOf(":") == -1)
			manualkeyword.push(originalkeywordparas[i]);
	}
	removeeleifempty(manualkeyword);
	//console.log(originalkeywordparas);
}
function buildfinalkeyword() {
    var finalkeyword = [];
	finalkeyword = finalkeyword.concat(manualkeyword, keyword);
	removeeleifempty(finalkeyword);
    //$("#keyword").val(finalkeyword.join(" "));
    return finalkeyword.join(" ");
}
function clearbtngroupvalue(id) {
	$("#" + id).find("label").removeClass("active").removeClass("disabled");
}
function getbtngroupvalue(id) {
	return $("#" + id).find(".active").children("input").val();
}
function getbtngrouptext(id) {
	return $("#" + id).find(".active").children("input").html();
}
function setbtngroupvalue(id, value) {
	$("#" + id).find("input").parent().removeClass('active');
	$("#" + id).find("input[value='" + value + "']").parent().addClass('active');
	//.not(this).removeClass("active");
	//console.log($("#" + id).find("input[value='" + value + "']"));
}
function setbtngroupenable(id, bool) {
	if(bool) {
		$("#" + id).find("label").removeClass("disabled");
	} else {
		$("#" + id).find("label").addClass("disabled");
	}
}

var sel_class = 'N';
function updatecaseclass() {
	//console.log(sel_class);
	setbtngroupvalue("casesys", sel_class);
	SelWord();
}
function checkcaseclass() {
	/*
	 * 法院級別 -> 可選裁判類別
	 * 名稱含訴願決定 ->A
	 * SHD		-> MVA
	 * YC		-> M
	 * BA		-> A
	 * P		-> P
	 * J		-> V
	*/
	
	//法院級別，S:最高院 H:高院 D:地院 A:最高行政 B:高等行政 P:公懲會
	var sel_court = $("#courtname")[0].value.substr(2,1);
	
	//選擇的法院名稱
	var sel_court_name = $("#courtname")[0].value.substr(4);
	
	if (sel_court_name.indexOf("訴願決定") != -1) {
		sel_class = "A";
		//f.v_sys[2].checked = true;
		//setbtngroupvalue("casesys", "A");
	} else {
		if ("D".indexOf(sel_court) != -1 ) {
			if ("VMA".indexOf(sel_class) == -1) {//20120821 BEN 地院加行政庭
				sel_class = "M";
				//f.v_sys[0].checked = true;
				//setbtngroupvalue("casesys", "M");
			}
		} else if ("SH".indexOf(sel_court) != -1 ) {
			if ("VM".indexOf(sel_class) == -1) {
				sel_class = "M";
				//f.v_sys[0].checked = true;
				//setbtngroupvalue("casesys", "M");
			}
		} else if ("YC".indexOf(sel_court) != -1 ) {
			//智財法院不限刑事民事行政
			//if(f.v_court.value.substr(0,3) == 'KSY'){
			if($("#courtname")[0].value.substr(0,3) == 'KSY'){
				sel_class = "V";
				//f.v_sys[1].checked = true;
				//setbtngroupvalue("casesys", "V");
			}
			//else if(f.v_court.value.substr(0,2) != 'IP'){
			else if($("#courtname")[0].value.substr(0,2) != 'IP'){
				if (sel_class != "M" ) {
					sel_class = "M";
					//f.v_sys[0].checked = true;
					//setbtngroupvalue("casesys", "M");
				}
			}
			else{
				//智財法院不可選公懲
				//if (f.v_sys[3].checked) {
				if(sel_class == "P") {
					sel_class = "M";
					//f.v_sys[0].checked = true;
					//setbtngroupvalue("casesys", "M");
				}
			}
		} else if ("BA".indexOf(sel_court) != -1 ) {
			if (sel_class != "A" ) {
				sel_class = "A";
				//f.v_sys[2].checked = true;
				//setbtngroupvalue("casesys", "A");
			}
		} else if ("P" == sel_court ) {
			if (sel_class != "P" ) {
				sel_class = "P";
				//f.v_sys[3].checked = true;
				//setbtngroupvalue("casesys", "P");
			}
		} else if ("U" == sel_court ) {
			if (sel_class == "S") {
				sel_class = "S";
				//f.v_sys[4].checked = true;
				//setbtngroupvalue("casesys", "S");
			}
		} 
	}
		
	updatecaseclass();
}
function SelWord() {
	//var f = document.form1;
	var i,sel_court,sel_court_name,selflag;

	//清除常用字別選單
	//for (i=f.sel_judword.options.length-1;i>=0;i--)
	//  f.sel_judword.options[i] = null;
	$("#caseword").select2("val", "");

	//法院級別，S:最高院 H:高院 D:地院 A:最高行政 B:高等行政 P:公懲會
	//sel_court = f.v_court.value.substr(2,1);
	sel_court = $("#courtname").select2("val").toString().substr(2,1);

	//選擇的法院名稱
	//sel_court_name = f.v_court.value.substr(4);
	sel_court_name = $("#courtname").select2("val").toString().substr(4);

	/*
	var courtid = document.form1.v_court.value.substr(0,3);
	if(courtid == "TPC" || courtid == "TPU" || courtid == "TPP" || (courtid == "TPH" && document.form1.v_sys[2].checked)){
		f.jcategory[0].checked = true;
	}
	*/

	//for (i=0;i<f.v_sys.length;i++)
	//    if ( f.v_sys[i].checked )
	//        sel_class = f.v_sys[i].value;  //審判別，A:行政  M:刑事  V:民事  P:公懲  S:訴願
	//sel_class = getbtngroupvalue("casesys");
	//console.log(sel_class);
	
	//依據所選法院及審判別變換常用字別

	selflag = true 
	if ( sel_court == "C" && sel_court_name == "司法院－刑事補償" ) {
		aa = new Array('常用字別','台覆');
		selflag = false
	}
	else if ( sel_court == "C" &&  sel_class == "A" ) {
		aa = new Array('常用字別','行商訴', '行專訴','行商更(一)');
		selflag = false
	}
	else if ( sel_court == "C" &&  sel_class == "V" ) {
		aa = new Array('常用字別', '民專訴', '民專上', '民著訴', '民商訴', '民專上易', '民專抗', '民商上', '民著上易', '民著上', '民商上易');
		selflag = false
	}
	else if ( sel_court == "C" &&  sel_class == "M" ) {
		aa = new Array('常用字別', '刑智上易', '刑智上訴', '刑智上更(一)', '刑智抗');
		selflag = false
	}   
	//if ( f.v_court.value.substr(0,3) == 'KSY' &&  sel_class == "V" ) {
	if ( $("#courtname").select2("val").toString().substr(0,3) == 'KSY' &&  sel_class == "V" ) {
	   aa = new Array('常用字別','訴', '重訴', '簡上', '簡', '婚', '勞訴', '保險', '家訴', '海商', '親', '勞簡上', '國貿', '小上', '家重訴', '國', '重勞訴', '訴更', '家聲', '重國', '聲');
	   selflag = false
	}
	if ( (sel_court == "D" || sel_court == "Y" ) &&  sel_class == "M" ) {
	   aa = new Array('常用字別','訴', '易', '自', '簡上', '簡', '訴緝', '易緝', '自緝', '重訴', '交易', '交訴', '交聲', '交簡上', '交自', '自更(一)', '訴更(一)', '易更(一)', '聲', '再', '附民');
	   selflag = false
	}
	if ( sel_court == "D" &&  sel_class == "V" ) {
	   aa = new Array('常用字別','訴', '重訴', '簡上', '簡', '婚', '勞訴', '保險', '家訴', '海商', '親', '勞簡上', '國貿', '小上', '家重訴', '國', '重勞訴', '訴更', '家聲', '重國', '聲');
	   selflag = false
	}
	if ( sel_court == "H" &&  sel_class == "V" )  { //民二
	   aa = new Array('常用字別','上', '重上', '上易', '家上', '上更(一)', '上更(二)', '重上更(一)', '重上更(二)', '重訴', '訴', '訴易', '勞上', '保險上', '海商上', '上國', '勞上易', '再易', '再');
	   selflag = false
	}
	if ( sel_court == "B" ) {
	   aa = new Array('常用字別','簡', '訴', '停', '聲');
	   selflag = false
	}
	if ( sel_court == "S" ) {
	   aa = new Array('常用字別','台上', '台抗', '台聲', '台職', '台再','台非', '台附', '台覆', '台賠', '特抗', '特聲', '特職', '特非', '特覆');
	   selflag = false
	}
	if ( sel_court == "A" ) {
	   aa = new Array('常用字別','判', '裁', '裁正', '裁止', '裁聲');
	   selflag = false
	}
	if ( sel_court == "U" ) {
	   aa = new Array('常用字別','再', '訴');
	   selflag = false
	}
	if ( sel_court == "P" ) {
	   aa = new Array('常用字別','鑑', '再審');
	   selflag = false
	}
	if ( sel_court == "H" && sel_class == "S" ) {
	   aa = new Array('常用字別','訴願');
	   selflag = false
	}
	if ( sel_court == "H" && sel_class == "A" ) {
	   aa = new Array('常用字別','訴願');
	   selflag = false
	}   
	//20120821 BEN 地院加行政庭
	if ( sel_court == "D" &&  sel_class == "A" ) {
	   aa = new Array('常用字別','交','簡', '訴', '停', '聲');
	   selflag = false;
	} 
	//20121225 BEN 司法院職務法庭 
	//if ( f.v_court.value.substr(0,3) == 'TPJ' ) {
	if ($("#courtname").select2("val").toString().substr(0,3) == 'TPJ' ) {
	   aa = new Array('常用字別','懲','訴','再','全','執','停','聲','他');
	   selflag = false;
	   //f.v_sys[4].checked = true;
	   //setbtngroupvalue("casesys", "S");
	   //for (i=0;i<f.v_sys.length-1;i++)
		//f.v_sys[i].disabled = true;
		setbtngroupenable("casesys", false);
	} else {
		//for (i=0;i<f.v_sys.length-1;i++)
		//	f.v_sys[i].disabled = false;
		setbtngroupenable("casesys", true);
	}
	if ( selflag ) { // 刑二 (其他)
	   aa = new Array('常用字別','抗', '上訴', '上易', '上更(一)', '上更(二)', '重上更(一)', '重上更(二)', '交上易', '上重訴', '交上訴', '少連上訴', '少上訴', '上重更(一)', '交上更(一)', '附民', '附民上', '上訴緝', '上易緝', '再', '再更(一)', '軍上');
	}

	/* if($("#courtname").select2("val") == "") {
		setbtngroupenable("casesys", false);
	}
	else {
		setbtngroupenable("casesys", true);
	} */

	//for (i=0;i<aa.length;i++)
	//    f.sel_judword.options[i] = new Option(aa[i],aa[i]);
	var data = [];
	//for (i=0;i<aa.length;i++)
	for (i=1;i<aa.length;i++)
		data.push({id:aa[i],text:aa[i]});

	$("#caseword").select2({
		allowClear: true,
		createSearchChoice:function(term, data) {
			if ($(data).filter(function() {
				return this.text.localeCompare(term)===0; }).length===0) {
				return {id:term, text:term};
			}
		},
		multiple: false,
		data: data
	});
	//$("#caseword").select2('data', data);
	//$("#caseword").select2('val', 0);
	//console.log(data);
}