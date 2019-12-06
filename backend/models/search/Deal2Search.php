<?php

namespace backend\models\search;

use common\models\Stock;
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
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
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
        return $dataProvider;
    }

    public function minSellMoney(){
        $sell=Deal2::find()
            ->select([
                'stock_id',
                'min(price) price',
                'round(min(price)*(1.01),2) as "1%_price"',
                'round(min(price)*(1.02),2) as "2%_price"',
                'round(min(price)*(1.04),2) as "4%_price"'
            ])
            ->andWhere(['is_sell'=>0])
            ->andWhere(['status'=>0])
            ->groupBy('stock_id')
            ->asArray()
            ->all();
        ;
        $stock=Stock::find()->indexBy('id')->asArray()->all();
        foreach ($sell as $k=>$v){
            $sell[$k]['stock']=$stock[$v['stock_id']];
        }
        return $sell;
    }

    public function winMoney(){
        $buy_money=Deal2::find()
            ->select(['sum(price)*100 as buy_money'])
            ->andWhere(['status'=>0])
            ->andWhere(['is_sell'=>1])
            ->asArray()
            ->one();

        $sell_money=Deal2::find()
            ->select(['sum(price)*100 as sell_money'])
            ->andWhere(['status'=>1])
            ->asArray()
            ->one();

        return ceil($sell_money['sell_money']-$buy_money['buy_money']);
    }
}
