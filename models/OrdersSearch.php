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
    public $count;
    //public $id_user;
    public $minPrice;
    public $maxPrice;
    public $minTotal;
    public $maxTotal;
    public $image;
    public $id_check;
    public $minDate;
    public $maxDate;
    public function rules()
    {
        return [
            [['id', 'id_product', 'count', 'id_check'], 'integer'],
            [['price', 'minPrice', 'maxPrice', 'minTotal', 'maxTotal'], 'double'],
            [['status', 'product', 'image', 'data', 'maxDate', 'minDate'], 'safe'],
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
                    'orderProduct.count',
                    'orderProduct.price',
                    '`orderProduct`.`price` * `orderProduct`.`count`',
                    'orderProduct.product',
                    'status',
                    'orderCheck.id_order',
                    'DATE(`data`)',
                    'id'
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith(['orderProduct']);
        $query->joinWith(['orderCheck']);
        //$query->joinWith(['user']);
        $name = User::find()->where("id=$userId")->asArray()->one();
        $query->where("orderProduct.user='$name[login]'");

        $query->andFilterWhere([
            'orderProduct.count' => $this->count,
            'image' => $this->image,
            //'users.id' => $this->id_user,
            'DATE(`data`)' => $this->data,
            'orderCheck.id_order' => $this->id_check
        ]);

//        var_dump($this->id_check);

        $query->andFilterWhere(['LIKE', 'orderProduct.product', $this->product]);
        $query->andFilterWhere(['LIKE', 'status', $this->status]);


        $query->andFilterWhere(['>=', new Expression('orderProduct.price * orderProduct.count'), $this->minTotal]);
        $query->andFilterWhere(['<=', new Expression('orderProduct.price * orderProduct.count'), $this->maxTotal]);

        $query->andFilterWhere(['>=', 'orderProduct.price', $this->minPrice]);
        $query->andFilterWhere(['<=','orderProduct.price', $this->maxPrice]);

        $query->andFilterWhere(['>=', 'data', $this->minDate]);
        $query->andFilterWhere(['<=','data', $this->maxDate]);

        $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
