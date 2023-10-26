<?php

namespace app\controllers;

use app\models\AuthAssignment;
use app\models\Products;
use app\models\User;
use app\models\UserSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['admin'],
                        ],
                        [
                            'allow' => false,
                            'roles' => ['*'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param string $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();
        $modelRole = new AuthAssignment();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $modelRole->load($this->request->post())) {

                if (!empty(User::find()->where("login='$model[login]'")->one())) {
                    \Yii::$app->session->setFlash('errorUser', 'Аn account with the same login already exists');
                    return $this->render('create', [
                        'model' => $model,
                        'modelRole' => $modelRole
                    ]);
                }
                $model->password = md5($model->password);

                $model->authKey = \Yii::$app->security->generateRandomString();

                if($model->save()) {
                    $modelRole->user_id = $model['id'];
                    $modelRole->created_at = time();
                    if($modelRole->save()) {
                        \Yii::$app->session->setFlash('successAdd', 'Added');
                        return $this->redirect(['index',]);
                    }
                    else {
                        \Yii::$app->session->setFlash('errorUser', 'Something went wrong');
                        return $this->render('create', [
                            'model' => $model,
                            'modelImages' => $modelRole
                        ]);
                    }
                }
                else {
                    \Yii::$app->session->setFlash('errorUser', 'Something went wrong');
                    return $this->render('create', [
                        'model' => $model,
                        'modelImages' => $modelRole
                    ]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model, 'modelRole' => $modelRole
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelRole = AuthAssignment::findOne(['user_id' => $id]);

        if ($model['assignment'][0]['item_name'] == 'admin'){
            \Yii::$app->session->setFlash('ErrorDelete', 'You do not have the right to update an admin');
            return $this->redirect(['index']);
        }

        if ($this->request->isPost) {
            $oldModel = clone $model;
            if ($model->load($this->request->post()) && $modelRole->load($this->request->post())) {

                if ($oldModel['login'] != $model['login']) {
                    if (!empty(User::find()->where("login='$model[login]'")->one())) {
                        \Yii::$app->session->setFlash('errorUser', 'Аn account with the same login already exists');
                        return $this->render('create', [
                            'model' => $model,
                            'modelRole' => $modelRole
                        ]);
                    }
                }
                if ($oldModel['password'] != $model['password']) {
                    $model->password = md5($model->password);
                }
                    if ($model->save()) {
                        if ($modelRole->save()) {
                            \Yii::$app->session->setFlash('successAdd', 'Update');
                            return $this->redirect(['index']);
                        } else {
                            \Yii::$app->session->setFlash('errorUser', 'Something went wrong');
                            return $this->render('update', [
                                'model' => $model,
                                'modelImages' => $modelRole
                            ]);
                        }
                    } else {
                        \Yii::$app->session->setFlash('errorUser', 'Something went wrong');
                        return $this->render('update', [
                            'model' => $model,
                            'modelImages' => $modelRole
                        ]);
                    }
                }
            }

        return $this->render('update', [
            'model' => $model, 'modelRole' => $modelRole
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)['assignment'][0]['item_name'] == 'admin'){
            \Yii::$app->session->setFlash('ErrorDelete', 'You do not have the right to remove an admin');
        }
        else {
            $this->findModel($id)->delete();
            AuthAssignment::findOne(['user_id' => $id])->delete();
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserSearch::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
