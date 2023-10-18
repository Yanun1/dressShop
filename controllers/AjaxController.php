<?php

namespace app\controllers;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\Products;
use Yii;


class AjaxController extends Controller
{
    private static $base;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin', 'employee'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function actionBase()
    {
        if (isset(self::$base))
            return json_encode(self::$base);
        else {
            self::$base = Products::find()->with('user')->with('images')->indexBy('id')->asArray()->all();
            return json_encode(self::$base);
        }
    }

    public static function productGet()
    {
        if(isset(self::$base))
            return self::$base;
        else {
            self::$base = Products::find()->with('user')->indexBy('id')->asArray()->all();
            return self::$base;
        }
    }
}