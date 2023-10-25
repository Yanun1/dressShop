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
    public $minPrice;
    public $maxPrice;
    public $minTotal;
    public $maxTotal;
    public $minDate;
    public $maxDate;
    public function rules()
    {
        return [
            [['id', 'id_product', 'count', 'id_check'], 'integer'],
            [['price', 'minPrice', 'maxPrice', 'minTotal', 'maxTotal'], 'double'],
            [['status', 'product', 'image', 'maxDate', 'minDate'], 'safe'],
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
        $query = Orders::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'count',
                    'price',
                    'employee',
                    '`price` * `count`',
                    'product',
                    'status',
                    'id',
                    'id_check'
                ],
            ],
        ]);


        $this->load($params);

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        // chjnjel petqa galu
//        $name = User::find()->where("id=$userId")->asArray()->one();
//        $query->where("orderProduct.user='$name[login]'");

//        $query->joinWith(['check']);
        $query->andFilterWhere([
            'count' => $this->count,
            'image' => $this->image,
            'id_check' => $this->id_check,
        ]);

        $query->andFilterWhere(['LIKE', 'product', $this->product]);
        $query->andFilterWhere(['LIKE', 'status', $this->status]);
        $query->andFilterWhere(['LIKE', 'employee', $this->employee]);


        $query->andFilterWhere(['>=', new Expression('price * count'), $this->minTotal]);
        $query->andFilterWhere(['<=', new Expression('price * count'), $this->maxTotal]);

        $query->andFilterWhere(['>=', 'price', $this->minPrice]);
        $query->andFilterWhere(['<=','price', $this->maxPrice]);

        $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
