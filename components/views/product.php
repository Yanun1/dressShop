<?php
use app\assets\ProductWidgetAsset;
use app\models\Products;
use app\controllers\AjaxController;

$data =  AjaxController::productGet();
ProductWidgetAsset::register($this);




$menuHtml;
$tree = [];

foreach ($data as $id => &$node) {
    if (!$node['id_product'])
        $tree[$id] = &$node;
    else
        $data[$node['id_product']]['childs'][$node['id']] = &$node;
}

function getMenuHtml ($tree) {
    $str = '';
    foreach ($tree as $category) {
        $str .= catToTemplate($category);
    }
    return $str;
}
 function catToTemplate ($category) {
     ob_start();
     include __DIR__ . '/menu_tpl/' . "menu.php";

     return ob_get_clean();
}
// echo '<pre>';
// var_dump($tree);
// echo '</pre>'; die;
$menuHtml= getMenuHtml($tree);
?>

<div class="select-product-widget">
    <div class="black-background"></div>
    <div class="select-window">
        <div class="window-content">
            <div class="window-title">
                <h2>Products List</h2>
                <hr>
            </div>
            <div class="window-product">
                <ul class="catalog">
                    <?= $menuHtml ?>
                </ul>
                <div class="product-info">
                    <div class="product-info-view">
                        <img src="http://dress-shop/images/window_product_default.jpg" alt="product">
                    </div>
                </div>
            </div>
            <div class="window-bottom">
                <hr>
                <div class="window-buttons">
                    <button class="btn btn-secondary choose-window">Choose</button>
                    <button class="btn btn-danger cancel-window">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    //var temp = '<?php //echo json_encode($data, true) ?>//';
    //console.log( JSON.parse(temp));
</script>