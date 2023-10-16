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
    public $minPrice;
    public $maxPrice;
    public $minTotal;
    public $maxTotal;
    public $image;
    public function rules()
    {
        return [
            [['id', 'id_product', 'count', 'id_user'], 'integer'],
            [['price', 'minPrice', 'maxPrice', 'minTotal', 'maxTotal'], 'double'],
            [['status', 'product', 'image'], 'safe'],
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
                ],
            ],
        ]);

//        $query->select([
//            '*',
//            'total' => new Expression('products.price * Orders.count'),
//            //new Expression('*, products.price * Orders.count AS `total`'),
//        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['user']);
        $query->joinWith(['product']);
        $query->where("users.id=$userId");

        $query->andFilterWhere([
            'products.product' => $this->product,
            'Orders.count' => $this->count,
            'status' => $this->status,
            'image' => $this->image,
            'users.id' => $this->id_user,
        ]);

        $query->andFilterWhere(['>=', new Expression('products.price * Orders.count'), $this->minTotal]);
        $query->andFilterWhere(['<=', new Expression('products.price * Orders.count'), $this->maxTotal]);

        $query->andFilterWhere(['>=', 'products.price', $this->minPrice]);
        $query->andFilterWhere(['<=','products.price', $this->maxPrice]);

        $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
