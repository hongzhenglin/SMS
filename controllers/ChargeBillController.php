<?php

namespace app\controllers;

use Yii;
use app\models\database\ChargeBill;
use app\models\database\chargeBillSerach;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChargeBillController implements the CRUD actions for ChargeBill model.
 */
class ChargeBillController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ChargeBill models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new chargeBillSerach();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ChargeBill model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    	$model = $this->findModel($id);
    	
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new ChargeBill model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ChargeBill();
		
		if ($model->load ( Yii::$app->request->post () )) {
			$model->status = 1;
			if (preg_match('/\d{4}-\d{2}-\d{2}\s*\d{2}:\d{2}:\d{2}/', $model->chargeTime)){
        		$model->chargeTime = strtotime($model->chargeTime);
        	}
			$model->recordTime = time();
			$model->updateTime = time();
			$model->save();
			return $this->redirect ( [ 
					'view',
					'id' => $model->id 
			] );
		} else {
			$model->chargeTime = date('Y-m-d H:i:s');
			return $this->render ( 'create', [ 
					'model' => $model 
			] );
		}
    }

    /**
     * Updates an existing ChargeBill model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
        	if (preg_match('/\d{4}-\d{2}-\d{2}\s*\d{2}:\d{2}:\d{2}/', $model->chargeTime)){
        		$model->chargeTime = strtotime($model->chargeTime);
        	}
        	
        	$model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
        	$model->chargeTime = date('Y-m-d H:i:s',$model->chargeTime);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ChargeBill model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ChargeBill model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ChargeBill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ChargeBill::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
