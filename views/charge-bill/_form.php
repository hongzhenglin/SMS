<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\database\MSCharge;
use dosamigos\datetimepicker\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\database\ChargeBill */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="charge-bill-form">
<?php
$providers = MSCharge::getAllProvider ();
$provinces = MSCharge::getAllProvince ();
$mobiles = MSCharge::getAllMobile ();
$simcards = MSCharge::getAllSimCard ();
?>

    <?php $form = ActiveForm::begin(['options'=> ['style'=>'margin:0px;display:inline;']]); ?>
     <table >
     <tr >
     <td>
     <?=$form->field ( $model, 'provider' )->dropDownList ( $providers, [ 'style' => 'width:150px','onchange' => '
           ' ] )->label ( '运营商' );?>
     </td>
     <td>
      <?=$form->field ( $model, 'province' )->dropDownList ( $provinces, [ 'style' => 'width:150px','prompt'=>'选择省份','onchange' => '
          onchangeProvince() ' ] )->label ( '省份' );?>
     </td>
     <td>
     <?=$form->field ( $model, 'mobile' )->dropDownList ( $mobiles, [ 'style' => 'width:150px','onchange' => '
           ' ] )->label ( '手机号' );?>
     </td>     
     <td>
      <?=$form->field ( $model, 'fee' )->dropDownList ( [ 50 => '50元',100 => '100元' ], [ 'style' => 'width:150px','onchange' => '
           ' ] )->label ( '金额' );?>
     </td>
     
      <?php 
      //$form->field ( $model, 'chargeTime' )->widget ( DateTimePicker::className (), [ 
//     		'language' => 'zh-CN','size' => 'ms','template' => '{input}','pickButtonIcon' => 'glyphicon glyphicon-time','inline' => true,
//     		'clientOptions' => [
//     				'startView' => 1,'minView' => 0,'maxView' => 4,'autoclose' => true,'linkFormat' => 'yyyy-mm-dd hh:ii:ss','inline' => true,'todayBtn' => true,'todayHighlight'=>true ]
//     ])->label ( '充值时间' )
?>
     <td>
     <?=$form->field($model, 'chargeTime')->widget(\nkovacs\datetimepicker\DateTimePicker::className(), [
     		'type'=> 'datetime',
     		'locale' => 'zh-cn',
     		'format'	=> 'yyyy-MM-dd HH:mm:ss',
     		'clientOptions' => [     			
		        'extraFormats' => ['yyyy-MM-dd HH:mm:ss'],
     			'showTodayButton'=>true
		    ],])?> 
     </td>
     </tr>
     </table>
         		
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script type="text/javascript">
<!--

//-->
var simcards = <?= json_encode($simcards)?>;
function onchangeProvince(){
	var provider = $('#chargebill-provider').val();
	var province = $('#chargebill-province').val();
	var html = "";
	$.each(simcards, function(i, simcard) {
		if(province == 0 || simcard.provider == provider && simcard.province == province){
			 html += '<option value="' + simcard['mobile'] + '">' + simcard['mobile'] + '</option>';
		}
	});	
	$('#chargebill-mobile').html(html);
}
   
</script>
