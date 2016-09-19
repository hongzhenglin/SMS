<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\database\chargeBillSerach */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="charge-bill-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'mobile') ?>

    <?= $form->field($model, 'imsi') ?>

    <?= $form->field($model, 'provider') ?>

    <?= $form->field($model, 'province') ?>

    <?php // echo $form->field($model, 'fee') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'chargeTime') ?>

    <?php // echo $form->field($model, 'recordTime') ?>

    <?php // echo $form->field($model, 'updateTime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
