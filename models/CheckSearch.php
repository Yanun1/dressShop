<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrderCheck;
use yii\db\Expression;

/**
 * CheckSearch represents the model behind the search form of `app\models\OrderCheck`.
 */
class CheckSearch extends OrderCheck
{
    public $Total_Price;
    public $Total_Count;
    public $data;
    public $minPrice;
    public $maxPrice;
    public $minCount;
    public $maxCount;
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
                    'id',
                    'orderCheck.id_order',
                    'Total_Price',
                    'Total_Count',
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
            'orderCheck.id, id_order, SUM(`price`*`count`) AS Total_Price, SUM(`count`) AS Total_Count'
            ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_order' => $this->id_order,
            'Total_Count' => $this->Total_Count,
            'Total_Price' => $this->Total_Price,
        ]);

        $query->having(['>=', 'Total_Price', $this->minPrice]);
        $query->having(['<=', 'Total_Price', $this->maxPrice]);

        $query->having(['>=', 'Total_Count', $this->minCount]);
        $query->having(['<=', 'Total_Count', $this->maxCount]);

        //$query->having(['>=', 'Orders.data', $this->minDate]);
        //$query->having(['<=', 'Orders.data', $this->maxDate]);


        $result = $query->asArray()->one();

        if ($result !== null) {
            $this->Total_Price = $result['Total_Price'];
            $this->Total_Count = $result['Total_Count'];
        }

        return $dataProvider;
    }
}
