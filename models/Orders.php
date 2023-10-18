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

            [['count', 'status'], 'required'],
            [['status'], 'string'],
            [['id_product'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['id_product' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],
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
            'count' => 'Count',
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



