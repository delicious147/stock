<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\core\Deal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deal-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'status')->dropDownList(\common\models\Deal2::getMap()) ?>
    <?= $form->field($model, 'stock_id')->dropDownList(\common\models\Stock::getMap()) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'num')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
