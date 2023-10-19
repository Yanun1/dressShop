<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orderProduct".
 *
 * @property int $id
 * @property string $product
 * @property float $price
 * @property int $count
 * @property string $user
 * @property string $employee
 * @property int|null $id_product
 *
 * @property Orders[] $orders
 * @property Products $product0
 */
class OrderProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orderProduct';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product', 'price', 'count', 'user', 'employee'], 'required'],
            [['price'], 'number'],
            [['count', 'id_product'], 'integer'],
            [['product'], 'string', 'max' => 45],
            [['user', 'employee'], 'string', 'max' => 15],
            [['id_product'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['id_product' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product' => 'Product',
            'price' => 'Price',
            'count' => 'Count',
            'user' => 'User',
            'employee' => 'Employee',
            'id_product' => 'Id Product',
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::class, ['id_product' => 'id']);
    }

    /**
     * Gets query for [[Product0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'id_product']);
    }

    public function getImages()
    {
        return $this->hasMany(ImagesProduct::class, ['id_product' => 'id_product']);
    }
}
