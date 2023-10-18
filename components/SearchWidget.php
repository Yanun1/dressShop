<?php

namespace app\components;

use yii\base\Widget;

class SearchWidget extends Widget
{
    public $model;
    public $fields;
    public $ranges;
    public $lists;

    public function init()
    {
        parent::init();

    }

    public function run()
    {
        return $this->render('search', ['model' => $this->model, 'fields' => $this->fields, 'ranges' => $this->ranges, 'lists' => $this->lists]);
    }
}