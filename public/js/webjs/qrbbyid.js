$(function(){

    $origin = $("#origin");
    $origin.removeClass("col-md-6");
    $origin.addClass("col-md-12");
    $dbContnet = $("#dbContent");
    $dbContnet.hide('slow');

    $("#btnqr").on("click" , function(){
        $id = $("#rid").val();
        $reg = /\d+/;
        $check = $reg.test($id);
        $contenter = $("#refereebookData");
        if($check){
            $contenter.text("查詢中，請稍後。");
            $.post( baseURL + "spider/getrefereebookfilebyid" , {
                id: $id,
                type : "refereebook"
            } , function(data){
                $contenter.text(data.path + "\r\n" + data.data );
                $tds = $("#dbdata tr td");
                console.log($tds);
                for(var i=0;i<=15;i++){
                   // $tds[i].text(data.dbdata[i]);
                    $tds[i].innerHTML = data.dbdata[i]
                    console.log(data.dbdata[i]);
                }
                $dbContnet.show('fast');
                $("#origin").removeClass('col-md-12').addClass('col-md-6');
                $dbContnet.show();
            }).fail(function(){
                $contenter.text("此ID查無資料");
            });
        }else{
            $contenter.text("ID 錯誤");
        }
        return false;
    });

    $("#btnqrlog").on("click" , function(){
        $dbContnet.hide('slow');
        $("#origin").addClass('col-md-12').removeClass('col-md-6');
        $id = $("#lid").val();
        $check = /\d+/.test($id);
        $contenter = $("#refereebookData");
        if($check){
            $contenter.text("查詢中，請稍後。");
            $.post( baseURL + "spider/getrefereebookfilebyid" , {
                id: $id,
                type: "log"
            } , function(data){
                $contenter.text(data.path + "\r\n" + data.data );
            }).fail(function(){
                $contenter.text("此ID查無資料");
            });
        }else{
            $contenter.text("ID 錯誤");
        }
        return false;
    });

});