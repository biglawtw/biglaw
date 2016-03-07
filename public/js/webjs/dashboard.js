$(function () {

    Highcharts.setOptions({
        global: {
            useUTC: false,
            timezoneOffset: -8 * 60
        }
    });

    $.get( baseURL + "spider/getlognumapi", function (data) {
        // 目前判決書數量 ， Ajax 更新
        var jupCountUp = new countUp("jupCount", 0, data, 0, 1.5);
        jupCountUp.start(function () {
            setInterval(function () {
                $.get( baseURL + "spider/getlognumapi", function (data) {
                    jupCountUp.d.innerHTML = jupCountUp.formatNumber(data);
                });
            }, 3000);
        });
    });

    $.get( baseURL + "spider/getunfinishsubtaskapi", function (data) {
        // 未完成任務數量
        var unfinishsubtaskcount = new countUp("uninishedsubtaskcount", 0, data, 0, 1.5);
        unfinishsubtaskcount.start(function () {
            setInterval(function () {
                $.get( baseURL + "spider/getunfinishsubtaskapi", function (data) {
                    unfinishsubtaskcount.d.innerHTML = unfinishsubtaskcount.formatNumber(data);
                })
            }, 5000);
        });
    });

    setInterval(function () {
        // 最後資料時間
        $.get( baseURL + "spider/getlastlogtime", function (data) {
            $("#lasttimelog").text(data);
        });
    }, 3000);

    function getServerStatus() {
        $.get( baseURL + "spider/getserverstatus/", function (data) {
            // 0 -> ok
            // 1 -> bad
            // 2-> repair
            if (data == 0) {
                $("#serverstatus").text("良好 ").append("<span class=\"glyphicon glyphicon-ok-sign text-success\"></span>");
            } else if (data == 1) {
                $("#serverstatus").text("離線 ").append("<span class=\"glyphicon glyphicon-remove-sign text-danger\"></span>");
            } else if (data == 2) {
                $("#serverstatus").text("維修 ").append("<span class=\"glyphicon glyphicon-question-sign text-primary\"></span>");
            }
        });
    }

    getServerStatus();
    setInterval(getServerStatus, 60000);

    $.get( baseURL + "spider/getcourtcountapi", function (data) {
        $("#averagedata").highcharts({
            chart: {
                height: 230,
                type: 'column'
            },
            credits: {
                enabled: false
            },
            title: {
                text: "各法院案件數量"
            },
            xAxis: {
                type: "category",
                labels: {
                    formatter: function () {
                        return this.value.substr(0, 3);
                    }
                }
            }, legend: {
                enabled: false
            },
            yAxis: {
                title: {
                    text: "案件數量"
                },
                labels: {
                    formatter: function () {
                        return this.value;
                    }
                }
            },
            series: [
                {
                    name: "各法院案件數量",
                    data: data
                }
            ]
        });
    });

    $.get( baseURL + "spider/getaveragespenttime", function (data) {
        var averageTimeChart = $("#avgtimechart").highcharts({
            chart: {
                height: 200,
                type: 'spline',
                zoomType: 'x'
            },
            credits: {
                enabled: false
            },
            title: {
                text: '最近平均耗時'
            },
            plotOptions: {
                spline: {
                    showInLegend: false
                }
            },
            tooltip: {
            },
            xAxis: {
                type: 'datetime'
            },
            yAxis: {
                title: {
                    text: "Times (second)"
                }
            },
            series: [
                {
                    name: "Average Times",
                    data: data
                }
            ]
        });
    });


    setInterval(function () {
        $.get( baseURL + "spider/getaveragespenttime", function (data) {
            var chart = $("#avgtimechart").highcharts();
            chart.series[0].setData(data);
            chart.redraw();
            chart.reflow();
        });
    }, 2000);

    $.get( baseURL + "spider/getsplitterlogstatusapi", function (data) {
        $('#errorinformationchart').highcharts({
            chart: {
                height: 200,
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            credits: {
                enabled: false
            },
            title: {
                text: '分析資訊',
                align: 'left',
                floating: true
            },
            tooltip: {
                pointFormat: '{series.name}:  <b>{point.percentage:.1f}% </b>({point.y})'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false,
                        format: '<b>{point.name}</b>: {point.percentage:.1f}%',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    showInLegend: true,
                    center: ['40%', '50%']
                }
            },
            legend: {
                align: 'right',
                verticalAlign: 'center',
                //width:150,
                floating: true,
                layout: 'vertical'
            },
            series: [
                {
                    type: 'pie',
                    name: '結果',
                    data: data
                }
            ]
        });
    });

    setInterval(function () {
        $.get( baseURL + "spider/getsplitterlogstatusapi", function (data) {
            var chart = $("#errorinformationchart").highcharts();
            chart.series[0].setData(data);
            chart.redraw();
            chart.reflow();
        });
    }, 5000);

    $(window).resize(function () {
        $("#errorinformationchart").highcharts().reflow();
        $("#errorinformationchart").highcharts().redraw();
        $("#avgtimechart").highcharts().reflow();
        $("#avgtimechart").highcharts().redraw();
        $("#averagedata").highcharts().reflow();
        $("#averagedata").highcharts().redraw();
    });

});