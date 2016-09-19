<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\database\ChargeBill */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="charge-bill-form">

<?php print_r($provinces)?>

    <?php $form = ActiveForm::begin(['options'=> ['style'=>'margin:0px;display:inline;']]); ?>
     <?=$form->field ( $model, 'provider' )->dropDownList ( [ 1 => '移动',2 => '联通',3 => '电信' ], [ 'style' => 'width:150px','prompt' => '请选择运营商','onchange' => '
           ' ] )->label ( '运营商' );?>
 <?=$form->field ( $model, 'province' )->dropDownList ( $provinces, [ 'style' => 'width:150px','prompt' => '请选择省','onchange' => '
           ' ] )->label ( '省份' );?>
          <?=$form->field ( $model, 'mobile' )->dropDownList ( [ 1 => '移动',2 => '联通',3 => '电信' ], [ 'style' => 'width:150px','prompt' => '请选择手机号','onchange' => '
           ' ] )->label ( '手机号' );?>
    


    <?= $form->field($model, 'imsi')->textInput(['maxlength' => true])?>

 <?=$form->field ( $model, 'fee' )->dropDownList ( [ 50 => 50,100 => 100 ], [ 'style' => 'width:150px','prompt' => '请选择金额','onchange' => '
           ' ] )->label ( '金额' );?>


    <?= $form->field($model, 'status')->textInput()?>

    <?= $form->field($model, 'chargeTime')->textInput()?>

    <?= $form->field($model, 'recordTime')->textInput()?>

    <?= $form->field($model, 'updateTime')->textInput()?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
