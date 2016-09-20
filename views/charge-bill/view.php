<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\database\MSCharge;

/* @var $this yii\web\View */
/* @var $model app\models\database\ChargeBill */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Charge Bills', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="charge-bill-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        	['label' => "运营商",'value' => MSCharge::getProviderNameById($model->provider),],
        	['label' => "省份",'value' => MSCharge::getProvinceNameById($model->province),],
        	'mobile',
        	'fee',
        	['label' => "状态",'value' => MSCharge::getStatusMsg($model->status),],
        	['label' => "充值时间",'value' => date('Y-m-d H:i:s',$model->chargeTime),]    		
    ]]) ?>

</div>
