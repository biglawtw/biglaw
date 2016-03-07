$(function(){
    $("#splitterSelector").on("change" , function(){
        var s = $("#splitterSelector option:selected").text();
        var filename = s.match(/^[a-zA-Z0-9+\\]+={0,2}\.py/)[0];
        $("#splitterContent").text("");
        $("#splitterContent").removeClass("prettyprinted");
        $.post( baseURL + "spider/loadsplitter" , {
            filename: filename
        } , function(data){
            $("#splitterContent").text(data);
            prettyPrint();
        }).fail(function(){

        });
    });
});