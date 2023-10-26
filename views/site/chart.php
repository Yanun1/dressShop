<?php
use app\models\Products;
use app\models\Orders;
use app\models\ChartSettings;
use yii\helpers\Html;
use app\assets\ChartAsset;
use app\components\ProductWidget;

$this->registerJsFile('https://d3js.org/d3.v6.min.js');
ChartAsset::register($this);//css js kpcnelu hamar

if (isset($_GET['year']) && isset($_GET['month'])) {
    $show_year = $_GET['year'];
    $show_month = $_GET['month'];
}else{
    $show_year = date('Y');
    $show_month = date('m');
}

$selected_month = $show_month;
$selected_year = $show_year;
$first_day = date('Y-m-01', strtotime($selected_year . '-' . $selected_month . '-01'));
$last_day = date('Y-m-t', strtotime($selected_year . '-' . $selected_month));

if(isset($_GET['id_product']) && $_GET['id_product'] != '') {
    $id_product = $_GET['id_product'];
//    var_dump($id_product);die;
    $productName = Products::find()->where("id=$id_product")->one();
    $orders = Orders::find()
        ->asArray()
        ->joinWith('check')
        ->andFilterWhere(["=", 'product', $productName['product']])
        ->andFilterWhere(['BETWEEN', 'date', $first_day, $last_day])
        ->all();
} else {
    $orders = Orders::find()
        ->asArray()
        ->joinWith('check')
        ->andFilterWhere(['BETWEEN', 'date', $first_day, $last_day])
        ->all();
}


$data = [];

$xAxis = [];
$currentDate = strtotime($first_day);

$endDate = strtotime($last_day);
while ($currentDate <= $endDate) {
    $date = date('Y-m-d', $currentDate);
    $xAxis[] = date('d', $currentDate);

    $data[$date] = ['price' => 0, 'count' => 0];
    $currentDate = strtotime('+1 day', $currentDate);
}
foreach($orders as $order) {
    $date = date('Y-m-d', strtotime($order["check"]['date']));
    $price = (double)$order['price'];
    $count = (int)$order['count'];
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
if(!\Yii::$app->user->isGuest) {
    $id = \Yii::$app->user->getId();
    $model = ChartSettings::find()->with('user')->where("user_id=$id")->one();
}

if($_SERVER['REQUEST_METHOD'] === 'GET' && !\Yii::$app->user->isGuest){
    if (isset($_GET['selectTheme'])) {
        $model->theme = $_GET['selectTheme'];
    }
    if (isset($_GET['selectTotal'])){
        $model->total_color = $_GET['selectTotal'];
    }
    if (isset($_GET['textcolor'])){
        $model->text_color = $_GET['textcolor'];
    }
    if (isset($_GET['selectCount'])){
        $model->count_color	 = $_GET['selectCount'];
    }
}
if(!\Yii::$app->user->isGuest && !isset($model)) {
    $model = new ChartSettings();
    $model->user_id = \Yii::$app->user->getId();
}

if (isset($model->theme)){
    $selectTheme = $model->theme;
    $selectTotal = $model->total_color;
    $selectCount = $model->count_color;
    $textcolor = $model->text_color;
}else{
    $selectTheme = "#330033";
    $selectTotal = "#FF0033";
    $selectCount = "#33FF33";
    $textcolor = "#fff";
    if(!\Yii::$app->user->isGuest){
        $model->theme = "#330033";
        $model->total_color = "#FF0033";
        $model->count_color = "#33FF33";
        $model->text_color = "#fff";
    }
}
if(!\Yii::$app->user->isGuest)
    $model->save();
?>

<?php
use scotthuangzl\googlechart\GoogleChart;

$orders = Orders::find()->select(['product AS 0', 'SUM(`count`) AS `1`'])->groupBy(['product'])->orderBy(['`1`' => SORT_DESC])->asArray()->all();

$i = 1;
$newData = [];
$newData[0] = [0 => 'Product', 1 => 'Count'];

foreach ($orders as $order) {
    $newData[$i] = $order;
    $newData[$i][1] = (int)$newData[$i][1];
    $i++;
}



if(count($newData) > 6){
    for ($i = 6; $i <= count($newData); $i++) {
        $newData[5][0] = 'others';
        $newData[5][1] += $newData[$i][1];
        unset($newData[$i]);
    }
}

?>


<?php
$orders = Orders::find()->select(['product AS 0', 'SUM(`price`*`count`) AS `1`'])->groupBy(['product'])->orderBy(['`1`' => SORT_DESC])->asArray()->all();

$newPriceData = [];
$newPriceData[] = ['Product', 'Price'];

foreach ($orders as $order) {
    $newPriceData[] = [$order[0], (int)$order[1]];
}

if(count($newPriceData) > 6){
    for ($i = 6; $i <= count($newPriceData); $i++) {
        $newPriceData[5][0] = 'others';
        $newPriceData[5][1] += $newPriceData[$i][1];
        unset($newPriceData[$i]);
    }
}

?>

<div class="cycle-charts">
    <?php
    echo GoogleChart::widget(array(
        'visualization' => 'PieChart',
        'data' => $newData,
        'options' => [
            'title' => 'Monthly Count Data',
            'titleTextStyle' => ['color' => '#871b47', 'fontSize' => 20],
            'backgroundColor' => '#fff',
            'chartArea' => ['width' => '60%'],
            'pieSliceTextStyle' => ['color' => 'white'],
            // Add more style options as needed
        ],
    ));
    ?>

    <?php
    echo GoogleChart::widget(array(
        'visualization' => 'PieChart',
        'data' => $newPriceData,
        'options' => [
            'title' => 'Monthly Price Data',
            'titleTextStyle' => ['color' => '#871b47', 'fontSize' => 20],
            'backgroundColor' => '#fff',
            'chartArea' => ['width' => '60%'],
            'pieSliceTextStyle' => ['color' => 'white'],
            // Add more style options as needed
        ],
    ));
    ?>

</div>


<div id="chartContainer" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>

<form action="" method="GET" class="settings-column">
<?= Html::dropDownList('year', $show_year, [
        "2022" => "2022",
        "2023" => "2023",
        "2024" => "2024",
    ]) ?>
    <?= Html::dropDownList('month', $show_month, [
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
    <?= Html::input('text', 'id_product', $_GET['id_product'] ?? '', ['placeholder' => 'Select product', 'class' => 'productInput form-control', 'readonly' => true]) ?>
    <button class="btn btn-primary">Show</button>
    <div class="color-bars">
        <button class="btn btn-dark">Change chart colors</button>
        <div class="chart-open">
            <form action="" method="GET">
                <label for="favcolor">Select theme:</label>
                <input type="color" class="selectTheme" name="selectTheme" value="<?=$selectTheme?>">
                <label for="favcolor">Select total color:</label>
                <input type="color" class="favcolor" name="selectTotal" value="<?=$selectTotal?>">
                <label for="favcolor">Select count color:</label>
                <input type="color" class="favcolor" name="selectCount" value="<?=$selectCount?>">
                <label for="favcolor">Select text color:</label>
                <input type="color" class="textcolor" name="textcolor" value="<?=$textcolor?>">
                <button class="btn btn-success save-button" style="background-color:#0d6efd; border:none">Save</button>
            </form>
        </div>
    </div>
</form>
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
            let selectedThemeColor = $(this).val();
            chart.options.backgroundColor = selectedThemeColor;
            chart.render();
        });
        $(".favcolor").change(function() {
            let selectedColor = $(this).val();
            let colorType = $(this).attr("name");
            if (colorType === "selectTotal") {
                chart.options.data[0].color = selectedColor;
            } else if (colorType === "selectCount") {
                chart.options.data[1].color = selectedColor;
            }
            chart.render();
        });
        $(".textcolor").change(function() {
            let selectedTextColor = $(this).val();
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
        $(document).ready(function(){
            let action = false;
            $(".btn.btn-dark").click(function(event){
                event.preventDefault();
                if (action) {
                    $(".chart-open").css("display", "none");
                } else {
                    $(".chart-open").css("display", "flex");
                }
                action = !action;
            });
        });
    }
</script>
<?php
echo ProductWidget::widget();
?>
<div style="overflow-x: scroll;">
    <div id="plotly" style="height: 600px; width: 600px;"></div>
</div>



<?php
$employersReport = Orders::find()
    ->asArray()
    ->select('employee, SUM(Orders.price*Orders.count) AS total, DATE(date) as date')
    ->joinWith('check')
    ->groupBy(['employee','DATE(date)'])
    ->all();


$tempData = [];
foreach ($employersReport as $item) {
    if(!isset($tempData[$item['employee']])) {
        $tempData[$item['employee']] = [];
        $tempData[$item['employee']]['price'] = [];
        $tempData[$item['employee']]['date'] = [];
        $tempData[$item['employee']]['employee'] = $item['employee'];
    }
    $tempData[$item['employee']]['price'][] = $item['total'];
    $tempData[$item['employee']]['date'][] = $item['date'];
}

$data3D = [];
$j = 0;
$x = 2;
foreach ($tempData as $item) {
    $data3D[$j] = [];
    for ($i = 1; $i < date('d', strtotime($last_day)); $i++) {
        $data3D[$j]['x'][] = [$x, $x+1];
        $data3D[$j]['y'][] = [$i,$i];

        for($t = 0; $t < count($item['date']); $t++) {
            if(date('d', strtotime($item['date'][$t])) == $i) {
                $data3D[$j]['z'][] = [$item['price'][$t], $item['price'][$t]];
                break;
            }
        }

        if(empty($data3D[$j]['z'][$i-1])) {
            $data3D[$j]['z'][$i-1] = [0,0];
        }

        $data3D[$j]['name'] = $item['employee'];
        $data3D[$j]['showscale'] = false;
        $data3D[$j]['type'] = 'surface';
    }
    $j++;
    $x+=2;
}
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        d3.json('https://raw.githubusercontent.com/plotly/datasets/master/3d-ribbon.json', function(figure){
            let data3D = <?= json_encode($data3D) ?>;
            var layout = {
                title: {
                    text: 'Sales of all sellers',
                        //position: 'bottom center',
                    y:0.9,
                    font: {
                        size: 27
                    }
                },
                showlegend: false,
                autosize: true,
                width: 600,
                height: 600,
                scene: {
                    xaxis: {title: 'Employers'},
                    yaxis: {title: 'Days'},
                    zaxis: {title: 'Sold Outs'}
                }
            };
            Plotly.newPlot('plotly', data3D, layout);
        });
    });

</script>



