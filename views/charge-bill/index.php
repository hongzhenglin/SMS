<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\database\MSCharge;
/* @var $this yii\web\View */
/* @var $searchModel app\models\database\chargeBillSerach */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Charge Bills';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="charge-bill-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Charge Bill', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

        	['attribute'=>'mobile','filter' => MSCharge::getAllMobile(),'headerOptions' => ['width' => '180px']],
        	['attribute' => 'provider','format' => 'text','value' => function ($model) {return MSCharge::getProviderNameById ( $model->provider );},
        	'filter' => MSCharge::getAllProvider(), 'headerOptions' => ['width' => '100px'], ],
        	['attribute' => 'province','format' => 'text','value' => function ($model) {return MSCharge::getProvinceNameById ( $model->province );},
        	'filter' => MSCharge::getAllProvinceByProvider(),'headerOptions' => ['width' => '120px'] ],
            ['attribute'=>'fee','headerOptions' => ['width' => '80px']],
            ['attribute' => 'status','format' => 'text','value' => function ($model) {return MSCharge::getStatusMsg( $model->status );},
            'filter' => MSCharge::getAllStatusMsg(),'headerOptions' => ['width' => '100px'] ],
            ['attribute' => 'chargeTime','format' => 'text','value' => function ($model) {return date('Y-m-d H:i:s', $model->chargeTime );},'headerOptions' => ['width' => '200px'] ],
            // 'recordTime:datetime',
            // 'updateTime:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
