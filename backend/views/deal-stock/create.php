<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\core\Deal */

$this->title = 'Create Deal';
$this->params['breadcrumbs'][] = ['label' => $stock_name,'url' => '/deal-stock/index?stock_id='.$model['stock']['id']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
