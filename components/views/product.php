<?php
use app\assets\ProductWidgetAsset;
use app\models\Products;

$data = Products::find()->with('user')->indexBy('id')->asArray()->all();

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
            <ul class="catalog">
                <?= $menuHtml ?>
            </ul>
        </div>
    </div>
</div>
