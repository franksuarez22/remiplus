<?php

namespace app\controllers;

use Yii;
use app\models\Personas;
use app\models\PersonasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use mdm\admin\components\Helper;
use mdm\admin\models\User;
use mdm\admin\models\form\LoginFormDenunciante;
use app\components\ComplementFunctions as cf;
/**
 * PersonasController implements the CRUD actions for Personas model.
 */
class PersonasController extends Controller
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
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Personas models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new PersonasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Personas model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $btnEditar = '';
            if(Helper::checkRoute('update')){
              $btnEditar = Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote']);
            }
            return [
                    'title'=> "Personas #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]). $btnEditar
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Personas model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        extract($_GET);
        $login = false;
        $request = Yii::$app->request;
        if(isset($id)){
            $model =  Personas::findOne(['id_persona'=>$id, 'estatus' => true]);
        }else{
            $model = new Personas();  
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Personas",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-personas-pjax',
                    'title'=> "Personas",
                    'content'=>'<span class="text-success">Datos Guardados</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Guardar Nuevo',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Personas",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            $session = Yii::$app->session;

            // comprueba si una sesión está ya abierta
            if ($session->isActive){
                $model->telefono_contacto=$session['telefono'];
                $model->correo_electronico=$session['correo'];
                //buscar en SAIME o web services
                $persona = \app\models\Persona::find()->where(['cedula' => $session['cedula']])->one();
                if (isset($persona) && !empty($persona)) {
                    $model->id_nacionalidad=isset($persona->nacionalidad)&&$persona->nacionalidad=="VEN"?1:2;
                    $model->cedula=$persona->cedula;
                    $model->primer_nombre=$persona->primer_nombre;
                    $model->segundo_nombre=$persona->segundo_nombre;
                    $model->primer_apellido=$persona->primer_apellido;
                    $model->segundo_apellido=$persona->segundo_apellido;
                    $model->id_genero=isset($persona->sexo)&&$persona->sexo=="F"?1:2;
                    $model->fecha_nacimiento=$persona->fecha_nacimiento;
                }
            }
        
            if ($model->load($request->post()) && $model->save()) {
                $Usuario = User::findByCedula($model->cedula);
                if(isset($Usuario) && !empty($Usuario) && !is_null($Usuario)){
                    $login = true;
                }else{//No lo consiguío en user
                    //Procedo a crear un usuario con los datos de personas
                    $user = new User;
                    $user->username = $model->cedula;
                    $user->email = $model->correo_electronico;
                    $user->status = 10;
                    $user->generateAuthKey();
                    $user->cedula = $model->cedula;
                    $user->save();               
                    $auth = \Yii::$app->authManager;
                    $authorRole = $auth->getRole('Denunciante');
                    $auth->assign($authorRole, $user->id); 
                    $login = true;  
                }
                $LoginFormDenunciante = new LoginFormDenunciante(); 
                $LoginFormDenunciante->id_nacionalidad = $model->id_nacionalidad;
                $LoginFormDenunciante->cedula = $model->cedula;
                $LoginFormDenunciante->telefono = $model->telefono_contacto;
                $LoginFormDenunciante->correo = $model->correo_electronico;
                $LoginFormDenunciante->token = $session['token'];
                if(Yii::$app->getUser()->isGuest && $login==true && $LoginFormDenunciante->login()){//hago login buscando el usuario por la cédula
                    $rol = cf::rolUsuario(Yii::$app->user->getId());
                    if(in_array('Denunciante', $rol)){
                        return $this->redirect(['/incidencias/bandejaincidencias']);
                    }else{
                        return $this->redirect(['/denuncias/bandejadenuncias']);
                    }
                }
                return $this->redirect(['view', 'id' => $model->id_persona]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Personas model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Editar Personas #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-personas-pjax',
                    'title'=> "Personas #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Editar Personas #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id_persona]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Personas model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->estatus = false;
        $model->update();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-personas-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

     /**
     * Delete multiple existing Personas model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->estatus = false;
            $model->update();
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-personas-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the Personas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Personas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Personas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
