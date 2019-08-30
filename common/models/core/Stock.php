<?php

namespace common\models\core;

use Yii;

/**
 * This is the model class for table "stock".
 *
 * @property int $id
 * @property string $stock_name
 * @property int $stock_code
 */
class Stock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stock_code'], 'integer'],
            [['stock_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stock_name' => 'Stock Name',
            'stock_code' => 'Stock Code',
        ];
    }
}
