<?php

namespace app\controllers;
use yii\web\Controller;
use app\models\Products;
use Yii;


class AjaxController extends Controller
{
    public function actionBase() {
        if(Yii::$app->request->isAjax) {
            $arrayProduct = json_encode(Products::find()->with('user')->asArray()->all());
            return $arrayProduct;
        }
    }
}