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
    public $date;
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
            [['date', 'minPrice', 'maxPrice', 'minCount', 'maxCount', 'minDate', 'maxDate'], 'safe']
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

        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'sort' => [
//                'defaultOrder' => [
//                    'id_order'
//                ],
//            ],
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['LIKE', 'id_order', $this->id_order]);
        $query->andFilterWhere(['LIKE', 'status', $this->status]);
        $query->andFilterWhere(['LIKE', 'date', $this->date]);

        if(is_null($this->minDate)) {
            $this->minDate = date('Y-m').'-01';
        }
        if(is_null($this->maxDate)) {
            $this->maxDate = date('Y-m').'-'.date('t');
        }

        $query->having(
            ['AND',
                ['>=', 'price', $this->minPrice],
                ['<=', 'price', $this->maxPrice],
                ['>=', 'count', $this->minCount],
                ['<=', 'count', $this->maxCount],
                ['>=', 'date', $this->minDate],
                ['<=', 'date', $this->maxDate],
            ]
        );

//        echo '<pre>';
//        var_dump($dataProvider);
//        var_dump($query->asArray()->all());die;


        return $dataProvider;
    }
}

