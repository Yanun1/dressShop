<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orderCheck".
 *
 * @property int $id
 * @property int $id_order
 * @property string $customer
 * @property float $price
 * @property int $count
 * @property string $status
 * @property string|null $date
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
            [['id_order', 'customer', 'price', 'count'], 'required'],
            [['id_order', 'count'], 'integer'],
            [['price'], 'number'],
            [['status'], 'string'],
            [['date'], 'safe'],
            [['customer'], 'string', 'max' => 15],
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
            'id_order' => 'ID Check',
            'customer' => 'Customer',
            'price' => 'Total Price',
            'count' => 'Total Count',
            'status' => 'Status',
            'date' => 'Date',
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
