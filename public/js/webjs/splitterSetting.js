$(function () {
    $("#switchSplitter").hide();
    $("#save-success").hide();
    $("#save-error").hide();

    function saveSuccessAlert() {
        $("#save-success").fadeTo(500, 1);
        setTimeout(function () {
            $("#save-success").fadeTo(500, 0).slideUp('slow');
        }, 2000);
    }

    function saveErrorAlert() {
        $("#save-error").fadeTo(500, 1);
        setTimeout(function () {
            $("#save-error").fadeTo(500, 0).slideUp('slow');
        }, 2000);
    }

    $.get( baseURL + "spider/getissplitterrun", function (data) {
        if (data == 1) {
            $("#switchSplitter .btn-primary").addClass("active");
        } else {
            $("#switchSplitter .btn-danger").addClass("active");
        }
        $("#loaddingSplitter").hide();
        $("#switchSplitter").show();
    });

    $.get(baseURL + "spider/getrunsplitter", function (data) {

        $("#selectSplitter option").each(function () {
            if ($(this).text().indexOf(data.filename) == 0) {
                $(this).attr("selected", true);
            }
        });

        $("#splitterFilename").text(data.filename);

        $.post(baseURL + "spider/loadsplitter", {
            filename: data.filename
        }, function (data) {
            $("#splitterContent").text(data);
            prettyPrint();
        });


    });

    $("#switchSplitterOn").on("click", function () {
        $("#switchSplitterOFf").removeClass("active");
        $("#switchSplitterOn").addClass("active");
        $.post(baseURL + "spider/updatesplitterrun", {
            run: 1
        }, function (data) {
            console.log(data);
        });
    });

    $("#switchSplitterOFf").on("click", function () {
        $("#switchSplitterOn").removeClass("active");
        $("#switchSplitterOFf").addClass("active");
        $.post(baseURL + "spider/updatesplitterrun", {
            run: 0
        }, function (data) {
            console.log(data);
        });
    });

    $("#selectSplitter").on("change", function () {
        var s = $("#selectSplitter option:selected").text();
        var filename = s.match(/^[a-zA-Z0-9+\\]+={0,2}\.py/)[0];

        $("#splitterContent").removeClass("prettyprinted");
        $("#splitterContent").text("");

        $.post(baseURL + "spider/loadsplitter", {
            filename: filename
        }, function (data) {
            $("#splitterContent").text(data);
            prettyPrint();
        });
    });

    $("#saveSplitter").on('click', function () {
        var s = $("#selectSplitter option:selected").val();
        $.post(baseURL + "spider/updaterunsplitter", {
            id: s
        },function (data) {
            saveSuccessAlert();
        }).fail(function () {

        });

    });
});