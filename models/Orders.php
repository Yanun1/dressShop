<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Orders".
 *
 * @property int $id
 * @property string $product
 * @property float $price
 * @property int $count
 * @property string $image
 * @property string $status
 * @property string $employee
 * @property int $id_check
 *
 * @property OrderCheck $check
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
            [['product', 'price', 'count', 'image', 'status', 'employee', 'id_check'], 'required'],
            [['price'], 'number'],
            [['count', 'id_check'], 'integer'],
            [['status'], 'string'],
            [['product'], 'string', 'max' => 45],
            [['image'], 'string', 'max' => 100],
            [['employee'], 'string', 'max' => 15],
            [['id_check'], 'exist', 'skipOnError' => true, 'targetClass' => OrderCheck::class, 'targetAttribute' => ['id_check' => 'id']],
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
            'image' => 'Image',
            'status' => 'Status',
            'employee' => 'Employee',
            'id_check' => 'Id Check',
        ];
    }

    /**
     * Gets query for [[Check]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCheck()
    {
        return $this->hasOne(OrderCheck::class, ['id' => 'id_check']);
    }

    public function getImages()
    {
        return $this->hasMany(ImagesProduct::class, ['product' => 'product']);
    }
}
