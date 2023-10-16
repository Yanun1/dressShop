<?php

namespace app\controllers;

use app\models\Orders;
use app\models\OrdersSearch;
use app\widgets\Alert;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\ForbiddenHttpException;


/**
 * OrderController implements the CRUD actions for Orders model.
 */
class OrderController extends Controller
{

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
                        'actions' => ['index', 'delete'],
                        'roles' => ['user'],
                    ],
                ],
            ],
        ];
    }

    public function init()
    {
        parent::init();
        if(Yii::$app->user->isGuest) {
            $this->redirect('/site/login');
        }
    }

    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
        $dataProvider = new ArrayDataProvider(['allModels' => Orders::find()->where("id_user=$userId")->with('user')->with('product')->all()]);
//        echo '<pre>';
//        var_dump(Orders::find()->with('user')->with('product')->asArray()->all());
//        echo '</pre>';

        return $this->render('index', [
//            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate(){
        $model = new Orders();

        if(Yii::$app->request->isPost) {
            $postValues = Yii::$app->request->post()['Orders'];

            for($i = 0; $i < count($postValues['id_product']); $i++){
                $model = new Orders();
                $model['count'] = $postValues['count'][$i];
                $model['id_product'] = $postValues['id_product'][$i];
                $model['id_user'] = Yii::$app->user->getId();
                $model['status'] = 'waiting';
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

        return $this->render('create',compact('model'));
    }

    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $row =  $this->findModel($id);
        if ($row['status'] == 'waiting') {
            $row->delete();
            Yii::$app->getSession()->setFlash('success deleting',"The order has been deleted successfully.");
        }
        else
            Yii::$app->getSession()->setFlash('failed',"You can't delete order! He is not in waiting.");
        return $this->redirect(['index']);
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
