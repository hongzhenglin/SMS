<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\database\ChargeBill */

$this->title = 'Create Charge Bill';
$this->params ['breadcrumbs'] [] = [ 
		'label' => 'Charge Bills',
		'url' => [ 
				'index' 
		] 
];
$this->params ['breadcrumbs'] [] = $this->title;
?>
<div class="charge-bill-create">

	<h1><?= Html::encode($this->title) ?></h1>

    <?=$this->render ( '_form', [ 'model' => $model,'provinces' => $provinces ] )?>

</div>
