<?php

namespace app\models;

use app\models\Orders;
use Faker\Core\Number;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * OrdersSearch represents the model behind the search form of `app\models\Orders`.
 */
class OrdersSearch extends Orders
{
    public $product;
    public $price;
    public $data;
    public $minPrice;
    public $maxPrice;
    public $minTotal;
    public $maxTotal;
    public $image;
    public $id_check;
    public function rules()
    {
        return [
            [['id', 'id_product', 'count', 'id_user', 'id_check'], 'integer'],
            [['price', 'minPrice', 'maxPrice', 'minTotal', 'maxTotal'], 'double'],
            [['status', 'product', 'image', 'data'], 'safe'],
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
        $userId = \Yii::$app->user->id;
        $query = Orders::find();//->with('user')->with('product');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [

                ],
                'attributes' => [
                    'count',
                    'products.price',
                    '`products`.`price` * `Orders`.`count`',
                    'products.product',
                    'status',
                    'orderCheck.id_order',
                    'DATE(`data`)'
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
        $query->joinWith(['product']);
        $query->joinWith(['orderCheck']);
        $query->where("users.id=$userId");

        $query->andFilterWhere([
            'Orders.count' => $this->count,
            'image' => $this->image,
            'users.id' => $this->id_user,
            'DATE(`data`)' => $this->data,
            'orderCheck.id_order' => $this->id_check
        ]);

        $query->andFilterWhere(['LIKE', 'products.product', $this->product]);
        $query->andFilterWhere(['LIKE', 'status', $this->status]);


        $query->andFilterWhere(['>=', new Expression('products.price * Orders.count'), $this->minTotal]);
        $query->andFilterWhere(['<=', new Expression('products.price * Orders.count'), $this->maxTotal]);

        $query->andFilterWhere(['>=', 'products.price', $this->minPrice]);
        $query->andFilterWhere(['<=','products.price', $this->maxPrice]);

        $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
