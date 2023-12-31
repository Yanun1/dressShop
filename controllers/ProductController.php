<?php

namespace app\controllers;

use app\commands\RolesController;
use app\models\imagesForm;
use app\models\ImagesProduct;
use app\models\Products;
use app\models\ProductsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ProductController implements the CRUD actions for Products model.
 */
class ProductController extends Controller
{
    /**
     * @inheritDoc
     */
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
            ]
        );
    }

    /**
     * Lists all Products models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Products model.
     * @param string $id ID
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
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Products();
        $modelImages = new ImagesProduct();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $modelImages->load($this->request->post())) {

                $model->id_user = \Yii::$app->user->getId();

                if (\Yii::$app->request->post('category')) {
                    $model->id_product = 0;
                }

                if (!empty(Products::find()->where("product='$model[product]'")->one())) {
                    \Yii::$app->session->setFlash('errorOrder', 'А product with the same name already exists');
                    return $this->render('create', [
                        'model' => $model,
                        'modelImages' => $modelImages
                    ]);
                }

                $extraName = time();

                $model->image = UploadedFile::getInstance($model, 'image');
                $model->upload($extraName);
                $model->image = $model->image->baseName.$extraName.'.'.$model->image->extension;

                if (!$model->save()) {

                    \Yii::$app->session->setFlash('errorOrder', 'Something gone wrong');
                    return $this->refresh();
                }

                $modelImages->image = UploadedFile::getInstances($modelImages, 'image');
                $modelImages->upload($extraName);

                foreach ($modelImages->image as $image) {
                    $ImagesProduct = new ImagesProduct();
                    $ImagesProduct->image = $image->baseName.$extraName.'.'.$image->extension;
                    $ImagesProduct->product = $model->product;
                    $ImagesProduct->save();
                }
                \Yii::$app->session->setFlash('successAdd', 'Added');
                return $this->redirect(['index', 'id' => $model->id]);
            } else {
                $model->loadDefaultValues();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelImages' => $modelImages
        ]);
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelImages = ImagesProduct::find()->where("product='$model->product'")->all();
        $modelProductsImage = new imagesForm();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $modelProductsImage->load($this->request->post())) {

                $model->id_user = \Yii::$app->user->getId();

                if (\Yii::$app->request->post('category')) {
                    $model->id_product = 0;
                }

                $extraName = time();

                $modelProductsImage->image = UploadedFile::getInstances($modelProductsImage, 'image');


                if($modelProductsImage->image != []) {
                    $model->image = $modelProductsImage->image;
                    $modelProductsImage->upload($extraName);
                    $model->image = $model->image[0]->baseName . $extraName . '.' . $model->image[0]->extension;
                }

//                echo '<pre>';
//                var_dump($model->validate());die;

                if (!$model->save()) {
                    \Yii::$app->session->setFlash('errorOrder', 'Something gone wrong');
                    return $this->refresh();
                }


                if(isset($modelProductsImage->remaining) && count($modelProductsImage->remaining) != count($modelImages)) {
                    foreach ($modelImages as $item) {
                        if(!in_array($item['image'], $modelProductsImage->remaining)) {
                            $item->delete();
                        }
                    }
                }

                $modelProductsImage->image = UploadedFile::getInstances($modelProductsImage, 'images');
                $modelProductsImage->uploadImages($extraName);

//                echo '<pre>';
//                var_dump($modelProductsImage);die;

                foreach ($modelProductsImage->image as $image) {
                    $ImagesProduct = new ImagesProduct();
                    $ImagesProduct->image = $image->baseName.$extraName.'.'.$image->extension;
                    $ImagesProduct->product = $model->product;
                    $ImagesProduct->save();
                }
                \Yii::$app->session->setFlash('successAdd', 'Added');
                return $this->redirect(['index', 'id' => $model->id]);
            } else {
                $model->loadDefaultValues();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelImages' => $modelImages,
            'modelProductsImage' => $modelProductsImage
        ]);
    }

    /**
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $childs = Products::find()->where("id_product=$id")->all();
        if($childs != null) {
            foreach ($childs as $child) {
                $child['id_product'] = 0;
                $child->save();
            }
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id ID
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
