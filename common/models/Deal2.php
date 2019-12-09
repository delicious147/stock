<?php

namespace common\models;

use Yii;


class Deal2 extends core\Deal2
{
    public $stock_name;

    public function rules()
    {
        return [
            [['stock_id', 'num', 'status','price'], 'required'],
            [['stock_id', 'num', 'status', 'is_sell'], 'integer'],
            [['price'], 'number'],
            [['date','stock_name'], 'safe'],
            [['remark'], 'string', 'max' => 255],
        ];
    }



    public function getStock()
    {
        return $this->hasOne(Stock::className(), ['id' => 'stock_id']);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stock_id' => 'Stock ID',
            'stock_name' => 'Stock Name',
            'price' => 'Price',
            'num' => 'Num',
            'date' => 'Date',
            'remark' => 'Remark',
            'status' => 'Status',
            'is_sell' => 'Is Sell',
        ];
    }

    public static function getMap(){
        return [
//            ''=>'全部',
            '0'=>'买',
            '1'=>'卖'
        ];
    }

    public static function getIsSellMap(){
        return [
//            ''=>'全部',
            '0'=>'持仓',
            '1'=>'-'
        ];
    }

}
