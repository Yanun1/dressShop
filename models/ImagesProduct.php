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
            [['id_product'], 'required'],
            [['id_product'], 'integer'],
            ['image', 'file', 'maxSize' => 1024 * 1024 * 20, 'extensions' => 'png, jpg', 'maxFiles' => 5,  'tooBig' => 'The file is too large. Maximum size 20 MB.'],
            [['image'], 'string', 'max' => 100],
            [['id_product'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['id_product' => 'id']],
        ];
    }

    public function upload($name)
    {
        if (true) {
            foreach ($this->image as $file) {
                $file->saveAs('images/' . $file->baseName .$name. '.' . $file->extension);
            }
            return true;
        } else {
            var_dump('didn\'t pass validate');die;
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
            'image' => 'Image',
            'id_product' => 'Id Product',
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
}
