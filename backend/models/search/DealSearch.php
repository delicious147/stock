<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Deal;

/**
 * DealSearch represents the model behind the search form of `common\models\core\Deal`.
 */
class DealSearch extends Deal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'num','stock_id'], 'integer'],
            [['date'], 'safe'],
            [['price'], 'number'],
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
        $query = Deal::find()->alias('d1')
            ->select([
                '*',
                'TRUNCATE(price*(1.01),2) as "1%_price"',
                'TRUNCATE(price*(1.02),2) as "2%_price"',
                'TRUNCATE(price*(1.03),2) as "3%_price"',
                'TRUNCATE(price*(1.04),2) as "4%_price"',
                'TRUNCATE(price*(1.05),2) as "5%_price"',
                'if(is_sell=1,TRUNCATE((sell_price-price)*num,2),0) win_money'
            ])
        ;


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $query;
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'd1.id' => $this->id,
            'd1.price' => $this->price,
            'd1.num' => $this->num,
            'd1.date' => $this->date,
        ]);
        $query->orderBy(['is_sell'=>SORT_ASC,'d1.stock_id'=>SORT_DESC,'d1.price'=>SORT_ASC]);

        return $query;
    }

    public function minSellMoney(){
        $min=Deal::find()
            ->select([
                'stock_id',
                'min(price) price',
                'TRUNCATE(min(price)*(1.02),2) as "2%_price"',
                'TRUNCATE(min(price)*(1.04),2) as "4%_price"'
            ])
            ->andWhere(['is_sell'=>0])
            ->groupBy('stock_id')
            ->asArray()
            ->all();
        ;
        return $min;
    }

    public function BuyMoney(){
        $sql='
        select *,TRUNCATE(price*(0.98),2) as "2%_price",TRUNCATE(price*(0.96),2) as "4%_price" from (select * from deal where is_sell=1 order by sell_date)a group by stock_id
        ';
        $buy=\Yii::$app->db->createCommand($sql)->queryAll();
        return $buy;
    }
}
