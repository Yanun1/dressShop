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

    public function upload($extraName)
    {
        if (true) {
            $this->image[0]->saveAs('images/' . $this->image[0]->baseName . $extraName . '.' . $this->image[0]->extension);
            return true;
        } else {
            return false;
        }
    }
}