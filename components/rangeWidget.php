<?php
namespace app\components;
;

use yii\base\Widget;

class rangeWidget extends Widget
{
    public $model;
    public $inputs = [];
    public $form;
    public $title;

    public function init()
    {
        parent::init();

    }

    public function run()
    {
        return $this->render('range',
            ['model' => $this->model, 'form' => $this->form, 'inputs' => $this->inputs,'title' => $this->title]);
    }
}