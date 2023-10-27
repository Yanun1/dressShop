<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Products;

/**
 * ProductsSearch represents the model behind the search form of `app\models\Products`.
 */
class ProductsSearch extends Products
{
    public $minPrice;
    public $maxPrice;
    public $minCount;
    public $maxCount;

    public function rules()
    {
        return [
            [['id', 'count', 'id_product', 'id_user', 'minCount', 'maxCount'], 'integer'],
            [['product', 'image'], 'safe'],
            [['price', 'minPrice', 'maxPrice'], 'double'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Products::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'products.id',
                    'price',
                    'count',
                    'product',
                    'users.id'
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith(['user']);

        if(!isset(\Yii::$app->authManager->getRolesByUser(\Yii::$app->user->id)['admin']) )
        {
            $query->andFilterWhere([ 'id_user' => \Yii::$app->user->id ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'price' => $this->price,
            'count' => $this->count,
            'users.id' => $this->id_user,
        ]);

//        $query->andFilterWhere(['LIKE', 'id_order', $this->id_order]);
//        $query->andFilterWhere(['LIKE', 'data', $this->data]);

        $query->andFilterWhere(['LIKE', 'product', $this->product]);
        $query->andFilterWhere(['LIKE', 'products.id', $this->id]);

        $query->andFilterWhere(['>=', 'products.price', $this->minPrice]);
        $query->andFilterWhere(['<=','products.price', $this->maxPrice]);
        $query->andFilterWhere(['>=', 'products.count', $this->minCount]);
        $query->andFilterWhere(['<=','products.count', $this->maxCount]);

        return $dataProvider;
    }
}
