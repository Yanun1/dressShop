<?php

namespace app\models;

use app\models;

class imagesForm extends models
{
    public $image;

    public function rules()
    {
        return [
            ['image', 'file', 'maxSize' => 1024 * 1024 * 20, 'extensions' => 'png, jpg', 'tooBig' => 'The file is too large. Maximum size 20 MB.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'image' => 'Main photo',
        ];
    }
}