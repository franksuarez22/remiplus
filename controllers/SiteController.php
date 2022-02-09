<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\LoginFormDenunciante;
use app\models\ContactForm;
use app\components\ComplementFunctions as cf;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
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

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Login action para denunciante.
     *
     * @return Response|string
     */
    public function actionLogindenunciante()
    {
        extract($_POST);
        extract($_GET);
        //cf::iam($_POST,true);
        $input_token = $rtoken = $token = false;
        $model = new LoginFormDenunciante();
        $model->scenario = 'verifytoken';  
         
        if ($model->load(Yii::$app->request->post())) { 
            //$model->scenario = 'verifytoken';
            //cf::iam($model,true);      
            if(isset($model->token)){
                $tokenValido = cf::tokenValido($model); 
                //cf::iam([$Tokensesion, $model],true);
                if($tokenValido["valido"]==true){
                    //Crear sesión
                    $session = Yii::$app->session;
                    $session['id_nacionalidad'] = $model->id_nacionalidad;
                    $session['cedula'] = $model->cedula;
                    $session['telefono'] = $model->telefono;
                    $session['correo'] = $model->correo;
                    //busco datos en personas
                    $personas = \app\models\Personas::findOne([
                        'cedula' => $model->cedula, 
                        'estatus' => true
                    ]);
                    if(!isset($personas) && empty($personas) && is_null($personas)){
                        return $this->redirect(['/personas/create']);
                    }    
                    //Buscar datos en los web services
                    //Si consigo datos redireccionar a incidencias si no a personas
                    //Aqui debo iniciar sesión
                    return $this->redirect(['/incidencias/indexdenunciante']);
                }  
                $input_token = true;
                $rtoken = $tokenValido["vencido"];
                $model->addError('token', $tokenValido["mensaje"]);
            }else{             
                if($model->validate(['id_nacionalidad','cedula', 'telefono', 'correo'])){
                    $input_token = true;
                    $token = cf::enviarToken($model);
                }
            }     
        }
        return $this->render('login_denunciante', [
            'model' => $model,
            'input_token' => $input_token,
            'rtoken' => $rtoken,
            'token' => $token,
        ]);
    }
}
