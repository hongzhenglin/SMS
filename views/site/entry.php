<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin() ;
echo  $form->field($model,'name')->label('姓名');
echo  $form->field($model,'email')->label('邮箱');
?>
<div class="form-group">
	<?= Html::submitButton('Submit',['class'=>'btn btn-primary'])?>
</div>
<?php ActiveForm::end();?>