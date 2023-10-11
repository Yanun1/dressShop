<?php
namespace app\models;
use yii\base\Model;

class OrdersForm extends Model{

    public $products;
    public $client;
    public $price;
    public $count;
    public $sum;

    public function attributLabels(){
        return [
            'products' => 'Products',
            'client' => 'Client',
            'price' => 'Price',
            'count' => 'Count',
            'sum' => 'Sum',
        ];
    
    }

    public function rules(){
        return [
            [['products','client','price','count','sum'],'required','message' =>'Required'],
        ];
    }
    
}

?>