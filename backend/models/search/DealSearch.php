<?php

namespace backend\models\search;

use common\components\MyHelper;
use common\models\Stock;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Deal;
use Yii;

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
        $sell=Deal::find()
            ->select([
                'stock_id',
                'min(price) price',
                'round(min(price)*(1.01),2) as "1%_price"',
                'round(min(price)*(1.02),2) as "2%_price"',
                'round(min(price)*(1.04),2) as "4%_price"'
            ])
            ->andWhere(['is_sell'=>0])
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

    public function BuyMoney(){
        $stock=Stock::find()->indexBy('id')->asArray()->all();
        $buy=[];
        foreach ($stock as $k=>$v){
            $buy[$k]['stock']=$v;
            $deal1=Deal::find()->where(['stock_id'=>$v['id']])->orderBy('date desc')->indexBy('stock_id')->asArray()->one();
            $deal2=Deal::find()->where(['stock_id'=>$v['id']])->orderBy('sell_date desc')->indexBy('stock_id')->asArray()->one();
            $buy[$k]['price']=strtotime($deal1['date'])>strtotime($deal2['sell_date'])?$deal1['price']:$deal2['sell_price'];
            $buy[$k]['1%_price']=round($buy[$k]['price']*0.99,2);
            $buy[$k]['2%_price']=round($buy[$k]['price']*0.98,2);
            $buy[$k]['4%_price']=round($buy[$k]['price']*0.96,2);
        }
        return $buy;

    }

    public function winMoney(){
        $sql="select sum((sell_price-price)*num) money from deal where is_sell=1;";
        $data = Yii::$app->db->createCommand($sql)->queryOne();
        $money=$data['money'];
        $money=floor($money*(999/1000)*(9997.5/10000));
        return $money;
    }

    public function inMoney(){
        $sql="select sum(price*num) money from deal where is_sell=0;";
        $data = Yii::$app->db->createCommand($sql)->queryOne();
        $money=floor($data['money']);
        return $money;
    }


}
