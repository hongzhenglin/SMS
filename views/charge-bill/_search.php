<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\database\MSCharge;
use dosamigos\datetimepicker\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\database\chargeBillSerach */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$providers = MSCharge::getAllProvider ();
$provinces = MSCharge::getAllProvinceByProvider ();
$mobiles = MSCharge::getAllMobile ();
$simcards = MSCharge::getAllSimCard ();
?>

<div class="charge-bill-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

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
         
     </tr>
     </table>
    
    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
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
