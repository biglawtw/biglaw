$(function(){
    $id = $(this).find("td").first().text();
    //$(document).remove($table);
    $table = $("#subtask").first();

    $subtaskURI = baseURL + "spider/getsubtask/";

    //$table.hide().insertAfter($(this)).show("fast");
    $("#subtask table tbody tr").each(function(){
        $(this).remove();
    });
    $.get($subtaskURI + "?id=" + $id , function(data){
        $data = data;
        for(var i =0;i<$data.length;i++){
            $tr = "<tr><td>" +
                $data[i].id + "</td><td>" +
                $data[i].date +"</td><td>" +
                ($data[i].startTime?($data[i].total==0?100:Math.round($data[i].processing / $data[i].total*100*100)/100) + "% (" +
                    ($data[i].startTime?((($data[i].total==0)?0:$data[i].processing) + "/" +　$data[i].total):"/") + ")" :"/") +
                "</td><td>" + ($data[i].startTime?$data[i].startTime:"未開始") + "</td><td>" +
                ($data[i].finishtime?$data[i].finishtime:"未完成") + "</td></tr>";
            $("#subtask table tbody").append($tr);
            $("#subtask").off("click" , "**");
        }
    });

    setInterval(function(){
        $table = $("#subtask table tbody");
        if($id){
            $.get($subtaskURI + "?id=" + $id , function(data){
                $data = data;
                $isfinish = true;
                for(var i = 0 ; i < $data.length ; i ++){
                    var sid = $table.find("tr").eq(i).first().find("td").eq(0).html();
                    $table.find("tr").eq(i).removeClass("active");
                    if(sid == $data[i].id){
                        if($data[i].startTime){
                            $table.find("tr").eq(i).first().find("td").eq(3).text( $data[i].startTime  );
                            $table.find("tr").eq(i).first().find("td").eq(2).text( ($data[i].startTime?($data[i].total==0?100:Math.round($data[i].processing / $data[i].total*100*100)/100) + "% (" +
                                ($data[i].startTime?((($data[i].total==0)?0:$data[i].processing) + "/" +　$data[i].total):"/") + ")" :"/")  );
                        }
                        if($data[i].finishtime){
                            $table.find("tr").eq(i).first().find("td").eq(4).text( $data[i].finishtime);
                        }else{
                            $isfinish = false;
                        }
                    }
                    if($data[i].startTime){
                        if(!$data[i].finishtime){
                            $table.find("tr").eq(i).addClass("active");
                        }
                    }
                }
                if($isfinish){
                    window.location.reload();
                }
            });
        }
    } , 2000);

    setInterval(function(){
        $.get("/~vagrant/biglaw/spider/index");
    }, 60000);
});