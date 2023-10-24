<?php

namespace app\models;

use app\models;

class imagesForm extends models
{
    public $image;
    public $images;
    public $remaining;

    public function rules()
    {
        return [
            ['remaining', 'string'],
            ['image', 'file', 'maxSize' => 1024 * 1024 * 20, 'extensions' => 'png, jpg',
                'tooBig' => 'The file is too large. Maximum size 20 MB.'],
            ['images', 'file', 'maxSize' => 1024 * 1024 * 20, 'extensions' => 'png, jpg', 'maxFiles' => 5,
                'tooBig' => 'The file is too large. Maximum size 20 MB.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'image' => 'Main photo',
            'images' => 'Additional images (optional)',
        ];
    }

    public function upload($extraName)
    {
        if (true) {
            $this->image[0]->saveAs('images/' . $this->image[0]->baseName . $extraName . '.' .
                $this->image[0]->extension);
            return true;
        } else {
            return false;
        }
    }

    public function uploadImages($extraName)
    {
        foreach ($this->image as $file) {
            $file->saveAs('images/' . $file->baseName . $extraName . '.' . $file->extension);
        }
    }
}