<?php

namespace app\models;

use yii\web\UploadedFile;
use Yii;
use function PHPUnit\Framework\isEmpty;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $product
 * @property float $price
 * @property int|null $count
 * @property int $id_product
 * @property int|null $id_user
 *
 * @property Orders[] $orders
 * @property Users $user
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product', 'price', 'count', 'image'], 'required'],
            ['id_product', 'default', 'value' => 0],
            ['product', 'match', 'pattern' => '/^[a-zA-Z0-9\-\s]+$/', 'message' => 'Please enter only letters and numbers.'],
            ['image', 'file', 'maxSize' => 1024 * 1024 * 20, 'extensions' => 'png, jpg', 'tooBig' => 'The file is too large. Maximum size 20 MB.'],
            [['price'], 'double'],
            [['count', 'id_product', 'id_user'], 'integer'],
            [['count', 'id_product', 'id_user', 'price'], 'match', 'pattern' => '/^[0-9\s]+$/', 'message' => 'The value must be a positive number.'],
            [['product'], 'string', 'min' => 3, 'max' => 45],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    public function upload($extraName)
    {
        if ($this->validate()) {
            $this->image->saveAs('images/' . $this->image->baseName . $extraName . '.' . $this->image->extension);
            return true;
        } else {
            return false;
        }
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
            'id_product' => 'Id Product',
            'id_user' => 'Id User',
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }

    public function getImages()
    {
        return $this->hasMany(ImagesProduct::class, ['id_product' => 'id']);
    }
}