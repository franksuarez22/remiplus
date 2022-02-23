<?php

namespace mdm\admin\controllers;

use mdm\admin\components\UserStatus;
use mdm\admin\models\form\ChangePassword;
use mdm\admin\models\form\Login;
use mdm\admin\models\form\LoginFormDenunciante;
use app\models\Personas;
use mdm\admin\models\form\PasswordResetRequest;
use mdm\admin\models\form\ResetPassword;
use mdm\admin\models\form\Signup;
use mdm\admin\models\searchs\User as UserSearch;
use mdm\admin\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\base\UserException;
use yii\filters\VerbFilter;
use yii\mail\BaseMailer;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\components\ComplementFunctions as cf;
/**
 * User controller
 */
class UserController extends Controller
{
    private $_oldMailPath;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'logout' => ['post'],
                    'activate' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (Yii::$app->has('mailer') && ($mailer = Yii::$app->getMailer()) instanceof BaseMailer) {
                /* @var $mailer BaseMailer */
                $this->_oldMailPath = $mailer->getViewPath();
                $mailer->setViewPath('@mdm/admin/mail');
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        if ($this->_oldMailPath !== null) {
            Yii::$app->getMailer()->setViewPath($this->_oldMailPath);
        }
        return parent::afterAction($action, $result);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

    /**
     * Deletes an existing User model.
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
     * Login
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->getUser()->isGuest) {
            return $this->goHome();
        }

        $model = new Login();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                    'model' => $model,
            ]);
        }
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
        $input_token = $rtoken = $token = $login = false;
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
                    $session['token'] = $model->token;
                    //busco datos en personas y en user
                    $personas = \app\models\Personas::findOne([
                        'cedula' => $model->cedula, 
                        'estatus' => true
                    ]);
                    if(isset($personas) && !empty($personas) && !is_null($personas)){
                        $Usuario = User::findByCedula($model->cedula);
                        if(isset($Usuario) && !empty($Usuario) && !is_null($Usuario)){
                            $login = true;
                        }else{//No lo consiguío en user pero si en personas
                            //Procedo a crear un usuario con los datos de personas
                            if($personas->validate()){//si persona tiene los datos validos
                                $user = new User;
                                $user->username = $personas->cedula;
                                $user->email = $personas->correo_electronico;
                                $user->status = 10;
                                $user->generateAuthKey();
                                $user->cedula = $personas->cedula;
                                $user->save();               
                                $auth = \Yii::$app->authManager;
                                $authorRole = $auth->getRole('Denunciante');
                                $auth->assign($authorRole, $user->id);         
                                $login = true;
                            }else{
                                return $this->redirect(['/personas/create', 'id' => $personas->id_persona]);
                            }
                        }
                        if($login==true && $model->login()){//hago login
                            $rol = cf::rolUsuario(Yii::$app->user->getId());
                            if(in_array('Denunciante', $rol)){
                                return $this->redirect(['/incidencias/bandejaincidencias']);
                            }else{
                                return $this->redirect(['/denuncias/bandejadenuncias']);
                            }
                        }
                    }elseif(!isset($personas) && empty($personas) && is_null($personas)){
                        return $this->redirect(['/personas/create']);
                    }  
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

    /**
     * Logout
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->getUser()->logout();

        return $this->goHome();
    }

    /**
     * Signup new user
     * @return string
     */
    public function actionSignup()
    {
        $model = new Signup();
        if ($model->load(Yii::$app->getRequest()->post())) {
            if ($user = $model->signup()) {
                return $this->goHome();
            }
        }

        return $this->render('signup', [
                'model' => $model,
        ]);
    }

    /**
     * Request reset password
     * @return string
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequest();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
                'model' => $model,
        ]);
    }

    /**
     * Reset password
     * @return string
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPassword($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                'model' => $model,
        ]);
    }

    /**
     * Reset password
     * @return string
     */
    public function actionChangePassword()
    {
        $model = new ChangePassword();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->change()) {
            return $this->goHome();
        }

        return $this->render('change-password', [
                'model' => $model,
        ]);
    }

    /**
     * Activate new user
     * @param integer $id
     * @return type
     * @throws UserException
     * @throws NotFoundHttpException
     */
    public function actionActivate($id)
    {
        /* @var $user User */
        $user = $this->findModel($id);
        if ($user->status == UserStatus::INACTIVE) {
            $user->status = UserStatus::ACTIVE;
            if ($user->save()) {
                return $this->goHome();
            } else {
                $errors = $user->firstErrors;
                throw new UserException(reset($errors));
            }
        }
        return $this->goHome();
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
