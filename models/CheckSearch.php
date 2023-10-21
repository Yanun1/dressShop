<?php

namespace app\models;

use Cassandra\Date;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrderCheck;
use yii\db\Expression;
use yii\helpers\Console;

/**
 * CheckSearch represents the model behind the search form of `app\models\OrderCheck`.
 */
class CheckSearch extends OrderCheck
{
    public $Total_Price;
    public $Total_Count;
    public $data;
    public $minPrice = 0;
    public $maxPrice = 1000000;
    public $minCount = 1;
    public $maxCount = 100000;
    public $minDate;
    public $maxDate;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_order'], 'integer'],
            [['Total_Price', 'Total_Count'], 'double'],
            [['data', 'minPrice', 'maxPrice', 'minCount', 'maxCount', 'minDate', 'maxDate'], 'safe']
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
        $query = OrderCheck::find();


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [

                ],
                'attributes' => [
                    'orderCheck.id_order',
                    'Total_Price',
                    'Total_Count',
                    'data'
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith(['orders'])
            ->join('LEFT JOIN', 'orderProduct', 'Orders.id_product = orderProduct.id')
            ->groupBy(['id_order'])
            ->select([
            'orderCheck.id, MIN(`Orders`.`data`) AS data ,id_order, SUM(`price`*`count`) AS Total_Price, SUM(`count`) AS Total_Count'
            ]);

        $query->andFilterWhere(['LIKE', 'id_order', $this->id_order]);
        $query->andFilterWhere(['LIKE', 'data', $this->data]);

        if(is_null($this->minDate)) {
            $this->minDate = date('Y-m').'-01';
        }
        if(is_null($this->maxDate)) {
            $this->maxDate = date('Y-m').'-'.date('t');
        }

        $query->having(
            ['AND',
                ['>=', 'Total_Price', $this->minPrice],
                ['<=', 'Total_Price', $this->maxPrice],
                ['>=', 'Total_Count', $this->minCount],
                ['<=', 'Total_Count', $this->maxCount],
                ['>=', 'data', $this->minDate],
                ['<=', 'data', $this->maxDate],
            ]
        );



        //$query->having(['>=', 'Orders.data', $this->minDate]);
        //$query->having(['<=', 'Orders.data', $this->maxDate]);


        $result = $query->asArray()->one();

        if ($result !== null) {
            $this->Total_Price = $result['Total_Price'];
            $this->Total_Count = $result['Total_Count'];
        }

//        echo '<pre>';
//        var_dump($query);

        return $dataProvider;
    }
}
