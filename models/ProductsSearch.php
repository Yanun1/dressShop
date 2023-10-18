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

    public function rules()
    {
        return [
            [['id', 'count', 'id_product', 'id_user'], 'integer'],
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
                    'id',
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'price' => $this->price,
            'count' => $this->count,
            'product' => $this->product,
            'users.id' => $this->id_user,
        ]);

        $query->andFilterWhere(['like', 'product', $this->product])
            ->andFilterWhere(['like', 'image', $this->image]);

        $query->andFilterWhere(['>=', 'products.price', $this->minPrice]);
        $query->andFilterWhere(['<=','products.price', $this->maxPrice]);

        return $dataProvider;
    }
}
