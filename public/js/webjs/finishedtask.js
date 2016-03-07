$(function(){
    $("#subtask").hide();
    $selectid = null;
    $("#taskTable tbody > tr").not("#subtask").on('click' , function(){
        $table = $("#subtask").first();
        $id = $(this).find("td").first().text();
        if($selectid == $id){
            $table.toggle('slow');
        }else{
            $selectid = $id;
            $table.hide().insertAfter($(this));
            $("#subtask table tbody tr").each(function(){
                $(this).remove();
            });
            $.get( baseURL +  "spider/getsubtask/?id="+$id , function(data){
                for(var i =0;i<data.length;i++){
                    $tr = "<tr><td>" + data[i].id + "</td><td>" + data[i].date +"</td><td>" + ((data[i].total==0)?0:data[i].processing) + "/" + data[i].total + "</td><td>" + data[i].startTime + "</td><td>" + data[i].finishtime + "</td></tr>";
                    $("#subtask table tbody").append($tr);
                }
                $table.show('slow');
            });
            console.log($(this).offset().top);
            $("body").animate({
                scrollTop: $(this).offset().top - $(this).height() -20
            },"slow");
        }
    });
});