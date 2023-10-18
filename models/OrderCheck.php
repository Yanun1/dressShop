<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orderCheck".
 *
 * @property int $id
 * @property int $id_order
 *
 * @property Orders[] $orders
 */
class OrderCheck extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orderCheck';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_order'], 'required'],
            [['id_order'], 'integer'],
            [['id_order'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_order' => 'Id Order',
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::class, ['id_check' => 'id']);
    }
}
