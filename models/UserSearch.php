<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
    public $item_name;
    public $minDate;
    public $maxDate;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['login', 'password', 'authKey', 'date', 'item_name', 'minDate', 'maxDate'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'date' => 'Date',
            'item_name' => 'Type',
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
        $query = User::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'id',
                    'login',
                    'date',
                    'item_name'
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(is_null($this->minDate)) {
            $this->minDate = date('Y-m').'-01';
        }
        if(is_null($this->maxDate)) {
            $this->maxDate = date('Y-m').'-'.date('t');
        }

        $query->joinWith('assignment');

        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'login', $this->login]);
        $query->andFilterWhere(['like', 'item_name', $this->item_name]);
        $query->andFilterWhere(['>=', 'date', $this->minDate]);
        $query->andFilterWhere(['<=', 'date', $this->maxDate]);

//        echo '<pre>';
//        var_dump($query->asArray()->all()); die;

        return $dataProvider;
    }
}
