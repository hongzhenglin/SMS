<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\EntryForm;
use yii\base\Object;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout(){
		    if (isset($_SERVER['PHP_AUTH_DIGEST'])) { 
		    $header['AUTHORIZATION'] = $_SERVER['PHP_AUTH_DIGEST']; 
		} elseif (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) { 
		    $header['AUTHORIZATION'] = base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']); 
		} 
		if (isset($_SERVER['CONTENT_LENGTH'])) { 
		    $header['CONTENT-LENGTH'] = $_SERVER['CONTENT_LENGTH']; 
		} 
		if (isset($_SERVER['CONTENT_TYPE'])) { 
		    $header['CONTENT-TYPE'] = $_SERVER['CONTENT_TYPE']; 
		}
		var_dump($header);
    }
    
    public function actionSay(){
    		//$response = file_get_contents("php://input");
    		//获取域名或主机地址
    		print_r($_SERVER);
    	echo $_SERVER['HTTP_HOST']."<br>"; #localhost
    	
    	//获取网页地址
    	echo $_SERVER['PHP_SELF']."<br>"; #/blog/testurl.php
    	
    	//获取网址参数
    	echo $_SERVER["QUERY_STRING"]."<br>"; #id=5
    	
    	//获取用户代理
    	//echo $_SERVER['HTTP_REFERER']."<br>";
    	
    	//获取完整的url
    	echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    	echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
    	#http://localhost/blog/testurl.php?id=5
    	
    	//包含端口号的完整url
    	echo 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    	#http://localhost:80/blog/testurl.php?id=5
    	
    	echo $_SERVER['REQUEST_METHOD']; // GET  POST 
    	//只取路径
    	$url='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
    	echo dirname($url);
    }
    
    /**
     * 创建表单
     */
    public function actionEntry(){
    		$model = new EntryForm();
    		if( $model->load(Yii::$app->request->post()) && $model->validate() ){
    			//验证$model 收到的数据
    			
    			return $this->render('entry-confirm',['model' => $model]);
    		}else{
    			return $this->render('entry',['model' => $model]);
    		}
    }
    
    public function actionGet(){
//     	print_r($_SERVER);
//     	print_r($_POST);
//     	print_r($_REQUEST);
    	$model = new LoginForm();
    	if ($model->load(Yii::$app->request->post())) {
    		$data = $_POST; // file_get_contents("php://input");
    	}else{
    		$data = 'no';
    	}
    	return $data;
    	//var_dump( $data );
    	
    }
    
}
