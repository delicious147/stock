<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Deal2;

/**
 * Deal2Search represents the model behind the search form of `common\models\core\Deal2`.
 */
class Deal2Search extends Deal2
{
    public $stock_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'stock_id', 'num', 'status', 'is_sell'], 'integer'],
            [['price'], 'number'],
            [['date', 'remark'], 'safe'],
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
        $query = Deal2::find();
        $query->with('stock');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'stock_id' => $this->stock_id,
            'price' => $this->price,
            'num' => $this->num,
            'date' => $this->date,
            'status' => $this->status,
            'is_sell' => $this->is_sell,
        ]);

        $query->andFilterWhere(['like', 'remark', $this->remark]);

//        $query->orderBy('date desc');

//        $res=$query->asArray()->all();

        return $dataProvider;
    }
}
