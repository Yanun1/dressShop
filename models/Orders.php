<?php
namespace app\models;
use yii\base\Model;
use yii\db\ActiveRecord;

class Orders extends ActiveRecord{

    public function attributLabels(){
        return [
            'id_product' => 'Product',
            'count' => 'Count',
        ];
    
    }
    public function rules(){
        return [
            [['id_product', 'count'],'required','message' =>'Required'],
        ];
    }
    
}

?>