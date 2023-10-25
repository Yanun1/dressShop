<?php

namespace app\controllers;
use app\models\OrderProduct;
use app\models\Orders;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\Products;
use Yii;


class AjaxController extends Controller
{
    private static $base;
    private static $baseProduct;

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
        if (Yii::$app->request->isAjax) {
            if ($_POST['orderProduct'] == 1) {
                if (isset(self::$base))
                    return json_encode(self::$base);
                else {
                    self::$base = Products::find()->with('user')->with('images')->indexBy('id')->asArray()->all();
                    return json_encode(self::$base);
                }
            } elseif ($_POST['orderProduct'] == 0) {
                if (isset(self::$baseProduct))
                    return json_encode(self::$baseProduct);
                else {
                    self::$baseProduct = Orders::find()->with('images')->indexBy('id')->asArray()->all();
                    return json_encode(self::$baseProduct);
                }
            }
        }
    }

    public static function productGet()
    {
        if(isset(self::$base))
            return self::$base;
        else {
            self::$base = Products::find()->with('user')->with('images')->indexBy('id')->asArray()->all();
            return self::$base;
        }
    }
}