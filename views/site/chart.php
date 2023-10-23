<?php
use app\models\OrderProduct;
use app\models\Orders;
use yii\helpers\Html;
use app\assets\ChartAsset;

ChartAsset::register($this);

if (isset($_GET['year']) && isset($_GET['month'])) {
    $show_year = $_GET['year'];
    $show_month = $_GET['month'];
} else {
    $show_year = date('Y');
    $show_month = date('m');
}

    $selected_month = $show_month;
    $selected_year = $show_year;
    $first_day = date('Y-m-01', strtotime($selected_year . '-' . $selected_month . '-01'));
    $last_day = date('Y-m-t', strtotime($selected_year . '-' . $selected_month . '-01'));

$orders = Orders::find()
    ->asArray()
    ->with('orderProduct')
   // ->andFilterWhere(['BETWEEN', 'data', $first_day, $last_day])
    ->all();

// echo '<pre>';
// var_dump($orders);die;

$data = [];
$xAxis = [];
$currentDate = strtotime($first_day);

// echo '<pre>';
// var_dump($orders);die;

$endDate = strtotime($last_day);
    while ($currentDate <= $endDate) {
        $date = date('Y-m-d', $currentDate);
        $xAxis[] = date('d', $currentDate);
        $data[$date] = ['price' => 0, 'count' => 0];
        $currentDate = strtotime('+1 day', $currentDate);
    }
    foreach ($orders as $order) {
    // if(!isset($order['data'])){       
    //     echo '<pre>';
    //     var_dump($order);die;
    // }
        $date = date('Y-m-d', strtotime($order['data']));
        $price = (double)$order['orderProduct']['price'];
        $count = (int)$order['orderProduct']['count'];
        $data[$date]['price'] += $price;
        $data[$date]['count'] += $count;
    }
    $priceData = [];
    $countData = [];
    foreach ($data as $value) {
        $priceData[] = $value['price'];
        $countData[] = $value['count'];
    }

    $chartData = [];
    foreach ($xAxis as $index => $xValue) {
        $chartData[] = ['x' => (int)$xValue, 'y' => $priceData[$index]];
    }

    $countChartData = [];
    foreach ($xAxis as $index => $xValue) {
        $countChartData[] = ['x' => (int)$xValue, 'y' => $countData[$index]];
    }

    if(isset($_GET['selectTheme'])){
        $selectTheme = $_GET['selectTheme'];
    }else{
        $selectTheme= "#330033";
    }
    if(isset($_GET['selectTotal'])){
        $selectTotal = $_GET['selectTotal'];
    }else{
        $selectTotal= "#FF0033";
    }
    if(isset($_GET['selectCount'])){
        $selectCount = $_GET['selectCount'];
    }else{
        $selectCount= "#33FF33";
    }
    if(isset($_GET['textcolor'])){
        $textcolor = $_GET['textcolor'];
    }else{
        $textcolor= "#fff";
    }

?>

<form action="" method="GET">
    <?= Html::dropDownList('year', date('Y'), [
        "2022" => "2022",
        "2023" => "2023",
        "2024" => "2024",
    ]) ?>
    <?= Html::dropDownList('month', date('m'), [
        "1" => "January",
        "2" => "February",
        "3" => "March",
        "4" => "April",
        "5" => "May",
        "6" => "June",
        "7" => "July",
        "8" => "August",
        "9" => "September",
        "10" => "October",
        "11" => "November",
        "12" => "December"
    ]) ?>
    <button class="btn btn-primary save-button">Show</button>
    </form>
    <form action="" method="GET">
    <label for="favcolor">Select theme:</label>
    <input type="color" class="selectTheme" name="selectTheme" value="<?=$selectTheme?>">
    <label for="favcolor">Select total color:</label>
    <input type="color" class="favcolor" name="selectTotal" value="<?=$selectTotal?>">
    <label for="favcolor">Select count color:</label>
    <input type="color" class="favcolor" name="selectCount" value="<?=$selectCount?>">
    <label for="favcolor">Select text color:</label>
    <input type="color" class="textcolor" name="textcolor" value="<?=$textcolor?>">
    <button class="btn btn-success save-button">Save</button>
    </form>
    
    <div id="chartContainer" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
    <script>
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
}
    </script>    
    <?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['selectTheme'])) {
        $_SESSION['selectTheme'] = $_GET['selectTheme'];
    }
    if (isset($_GET['selectTotal'])) {
        $_SESSION['selectTotal'] = $_GET['selectTotal'];
    }
    if (isset($_GET['textcolor'])) {
        $_SESSION['textcolor'] = $_GET['textcolor'];
    }
    if (isset($_GET['selectCount'])) {
        $_SESSION['selectCount'] = $_GET['selectCount'];
    }
}

if (isset($_SESSION['selectTheme'])) {
    $selectTheme = $_SESSION['selectTheme'];
} else {
    $selectTheme = "#330033";
}
if (isset($_SESSION['selectTotal'])) {
    $selectTotal = $_SESSION['selectTotal'];
} else {
    $selectTotal = "#FF0033";
}
if (isset($_SESSION['selectCount'])) {
    $selectCount = $_SESSION['selectCount'];
} else {
    $selectCount = "#33FF33";
}

if (isset($_SESSION['textcolor'])) {
    $textcolor = $_SESSION['textcolor'];
} else {
    $textcolor = "#fff";
}
    ?>
