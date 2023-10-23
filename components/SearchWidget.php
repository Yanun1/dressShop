<?php

namespace app\components;

use ejfrias\yii2_xdan_datetimepicker\DateTimePicker;
use yii\base\Widget;


class SearchWidget extends Widget
{
    public $model;
    public $fields;
    public $ranges;
    public $lists;
    public $calendars;

    public function init()
    {
        parent::init();

    }

    public function run()
    {
        return $this->render('search', ['model' => $this->model, 'fields' => $this->fields, 'ranges' => $this->ranges, 'lists' => $this->lists, 'calendars' => $this->calendars]);
    }
}