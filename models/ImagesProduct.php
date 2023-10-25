<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "imagesProduct".
 *
 * @property int $id
 * @property string $image
 * @property int $id_product
 *
 * @property Products $product
 */
class ImagesProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'imagesProduct';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product'], 'required'],
            [['product'], 'string'],
            ['image', 'file', 'maxSize' => 1024 * 1024 * 20, 'extensions' => 'png, jpg', 'maxFiles' => 5,  'tooBig' => 'The file is too large. Maximum size 20 MB.'],
            [['image'], 'string', 'max' => 100],
            [['product'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product' => 'product']],
        ];
    }

    public function upload($name)
    {
        foreach ($this->image as $file) {
            $file->saveAs('images/' . $file->baseName .$name. '.' . $file->extension);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => 'Image',
            'product' => 'Product',
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['product' => 'product']);
    }
}
