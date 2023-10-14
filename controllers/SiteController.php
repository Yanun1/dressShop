<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Console;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Orders;
use app\commands\RolesController;
use app\models\Products;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'index', 'orders'],
                'rules' => [
                    [
                        'actions' => ['index', 'logout', 'orders'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],                    [
                        'actions' => ['index', 'logout'],
                        'allow' => true,
                        'roles' => ['employee'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['*'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        if(Yii::$app->authManager->getRole('admin') == null) {
            RolesController::defaultRoles();
        }
        return $this->render('index');
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionOrders(){
        $model = new Orders();

       if(Yii::$app->request->isPost) {
           $postValues = Yii::$app->request->post()['Orders'];
           for($i = 0; $i < count($postValues['id_product']); $i++){
               $model = new Orders();
               $model['count'] = $postValues['count'][$i];
               $model['id_product'] = $postValues['id_product'][$i];
               $model['id_user'] = Yii::$app->user->getId();
               if($model->validate())
                   $model->save();
               else {
                   Yii::$app->session->setFlash('errorOrder', 'Something gone wrong in'."$i row!");
                   return $this->refresh();
               }
           }
           Yii::$app->session->setFlash('successOrder', 'Ordered');
           return $this->refresh();
       }

        return $this->render('orders',compact('model'));
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return  $this->goHome();
    }
}

// registraciayi jamanak petqa authKey generacnenq ev tanq assign-ov roly userin
// $userRole = Yii::$app->authManager->getRole('admin');
// Yii::$app->authManager->assign($userRole, $model->getUser()->getId());
