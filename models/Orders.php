<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Orders".
 *
 * @property int $id
 * @property int|null $id_product
 * @property int $count
 * @property int|null $id_user
 * @property string $status
 *
 * @property Products $product
 * @property Users $user
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

//            [['status'], 'required'],
            [['status'], 'string'],
            [['status'], 'required'],
            [['id_product', 'data', 'id', 'id_check'], 'safe'],
            [['id_product'], 'exist', 'skipOnError' => true, 'targetClass' => OrderProduct::class, 'targetAttribute' => ['id_product' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_product' => 'Product',
            'id_user' => 'Id User',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'id_product']);
    }

    public function getOrderProduct()
    {
        return $this->hasOne(OrderProduct::class, ['id' => 'id_product']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }
    public function getOrderCheck()
    {
        return $this->hasOne(OrderCheck::class, ['id' => 'id_check']);
    }
}



