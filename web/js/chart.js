window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer", {
    animationEnabled: true,
    backgroundColor:"<?=$selectTheme?>",
    title: {
        fontColor:"<?=$textcolor?>",
        text: "Monthly Orders Data"
    },
    axisX: {
        labelFontColor:"<?=$textcolor?>",
        titleFontColor:"<?=$textcolor?>",
        title: "Days",
        interval: 1
    },
    axisY: [{
        labelFontColor:"<?=$textcolor?>",
        titleFontColor:"<?=$textcolor?>",
        title: "Price",
        prefix: "$"
    },{
        labelFontColor:"<?=$textcolor?>",
        titleFontColor:"<?=$textcolor?>",
        title: "count",
    }],
    toolTip: {
        shared: true
    },
    legend: {
        cursor: "pointer",
        itemclick: toggleDataSeries
    },
    data: [{
        type: "column",
        name: "Total",
        showInLegend: true,
        dataPoints: <?php echo json_encode($chartData); ?>,
        color:"<?=$selectTotal?>"
    },
    {
        type: "area",
        name: "Count",
        axisYIndex: 1,
        showInLegend: true,
        dataPoints: <?php echo json_encode($countChartData); ?>,
        color: "<?=$selectCount?>"
    },
    {
        type: "area",
        name: "Profit",
        showInLegend: true,
        dataPoints: []
    }]

});
chart.render();

function toggleDataSeries(e) {
    if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
        e.dataSeries.visible = false;
    }else{
        e.dataSeries.visible = true;
    }
    chart.render();
}
$(".selectTheme").change(function() {
            var selectedThemeColor = $(this).val();
            chart.options.backgroundColor = selectedThemeColor;
            chart.render();
        });

        $(".favcolor").change(function() {
            var selectedColor = $(this).val();
            var colorType = $(this).attr("name");

            if (colorType === "selectTotal") {
                chart.options.data[0].color = selectedColor;
            } else if (colorType === "selectCount") {
                chart.options.data[1].color = selectedColor;
            }
            chart.render();
        });

$(".textcolor").change(function() {
var selectedTextColor = $(this).val();
chart.options.title.fontColor = selectedTextColor;
chart.options.axisX.labelFontColor = selectedTextColor;
chart.options.axisX.titleFontColor = selectedTextColor;
chart.options.axisY[0].labelFontColor = selectedTextColor;
chart.options.axisY[0].titleFontColor = selectedTextColor;
chart.options.axisY[1].labelFontColor = selectedTextColor;
chart.options.axisY[1].titleFontColor = selectedTextColor;
chart.render();
});
chart.render();

$('.gtitle').css('font-size', '27px !important');
$('.gtitle').attr('y', '100 !important');

}