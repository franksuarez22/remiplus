<?php

namespace app\controllers;

use Yii;
use app\components\ComplementFunctions as cf;
use app\models\Incidencias;
use app\models\IncidenciasSearch;
use app\models\Personas;
use mdm\admin\models\User;
use app\models\Incidenciaspersonas;
use app\models\Incidenciasestatus;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use mdm\admin\components\Helper;
use yii\web\UploadedFile;
/**
 * IncidenciasController implements the CRUD actions for Incidencias model.
 */
class IncidenciasController extends Controller
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
     * Lists all Incidencias models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new IncidenciasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(Yii::$app->user->getId()!==null){
            $usuario = User::findIdentity(Yii::$app->user->getId());
            $Personas = Personas::findOne(['cedula'=>$usuario->cedula, 'estatus' => true]);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'Personas' => $Personas,
        ]);
    }

    public function actionIndexdenunciante()
    {    
        $searchModel = new IncidenciasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index_denunciante', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Incidencias model.
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
                    'title'=> "Incidencias #".$id,
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
     * Creates a new Incidencias model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Incidencias();  
        $Incidenciaspersonas = new Incidenciaspersonas();
        $Incidenciasestatus = new Incidenciasestatus();
        if(Yii::$app->user->getId()!==null){
            $usuario = User::findIdentity(Yii::$app->user->getId());
            $Personas = Personas::findOne(['cedula'=>$usuario->cedula, 'estatus' => true]);
            //cf::iam([$usuario,$Personas],true);
            $model->id_estado=$Personas->id_estado;
            $model->id_municipio=$Personas->id_municipio;
            $model->id_parroquia=$Personas->id_parroquia;
            $model->id_ciudad=$Personas->id_ciudad;
        }
        //cf::iam([$usuario,$Personas],true);
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Incidencias",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else{ 
                $model->load($request->post());                                             
                $valido = true;
                $valido = $model->validate() && $valido;

                $uploadedFile = UploadedFile::getInstance($model, 'imagen');
                if($uploadedFile){
                    $model->imagen = $uploadedFile->baseName. '.' . $uploadedFile->extension;
                }

                if($valido){
                    $transaction = $model::getDb()->beginTransaction();
                    try {
                        $guardado = true;
                        $guardado = $model->save(false) && $guardado;
                        $ultimoIdIncidencia=$model->primaryKey;

                        $Incidenciaspersonas->id_incidencia = $ultimoIdIncidencia;
                        $Incidenciaspersonas->id_persona = $Personas->id_persona;
                        $Incidenciaspersonas->validate();
                        $guardado = $Incidenciaspersonas->save(false) && $guardado;

                        /*$Incidenciasestatus->id_denuncia = $ultimoIdIncidencia;
                        $Incidenciasestatus->id_estatus_incidencia = 1;//Abierta
                        $Incidenciasestatus->fecha_cambio_estatus = date('Y-m-d');
                        $Incidenciasestatus->observaciones = 'Incidencia Abierta';
                        $Incidenciasestatus->validate();
                        $Incidenciasestatus->save(false);*/
                        if($guardado){
                            $transaction->commit();
                            if($uploadedFile){
                                Yii::$app->params['uploadPath']= Yii::$app->basePath.'/web/uploads/';
                                $uploadedFile->saveAs(Yii::$app->params['uploadPath'] . $uploadedFile->baseName. '.' . $uploadedFile->extension);
                            }
                            return [
                                'forceReload'=>'#crud-datatable-incidencias-pjax',
                                'title'=> "Incidencias",
                                'content'=>'<span class="text-success">Datos Guardados</span>',
                                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                        Html::a('Guardar Nueva',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    
                            ];
                        }     
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                            return [
                                'title'=> "Incidencias",
                                'content'=>'<span class="text-danger">Datos No Guardados</span>',
                                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                    
                            ]; 
                    }
                }
                return [
                    'title'=> "Incidencias",
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
            if ($model->load($request->post())) {
                $valido = true;
                $valido = $model->validate() && $valido; 

                $uploadedFile = UploadedFile::getInstance($model, 'imagen');
                if($uploadedFile){
                    $model->imagen = $uploadedFile->baseName. '.' . $uploadedFile->extension;
                }

                if($valido){
                    $transaction = $model::getDb()->beginTransaction();
                    try {
                        $guardado = true;
                        $guardado = $model->save(false) && $guardado; 
                        $ultimoIdIncidencia=$model->primaryKey;

                        $Incidenciaspersonas->id_incidencia = $ultimoIdIncidencia;
                        $Incidenciaspersonas->id_persona = $Personas->id_persona;
                        $Incidenciaspersonas->validate();
                        $guardado = $Incidenciaspersonas->save(false) && $guardado; 

                        /*$Incidenciasestatus->id_denuncia = $ultimoIdIncidencia;
                        $Incidenciasestatus->id_estatus_incidencia = 1;//Abierta
                        $Incidenciasestatus->fecha_cambio_estatus = date('Y-m-d');
                        $Incidenciasestatus->observaciones = 'Incidencia Abierta';
                        $Incidenciasestatus->validate();
                        $Incidenciasestatus->save(false);*/
                        if($guardado){
                            $transaction->commit();
                            if($uploadedFile){
                                Yii::$app->params['uploadPath']= Yii::$app->basePath.'/web/uploads/';
                                $uploadedFile->saveAs(Yii::$app->params['uploadPath'] . $uploadedFile->baseName. '.' . $uploadedFile->extension);
                            }
                            
                            return $this->redirect(['view', 'id' => $model->id_incidencia]);
                        } 
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                }
            }
            return $this->render('create', [
                'model' => $model,
            ]);
        }
       
    }

    /**
     * Updates an existing Incidencias model.
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
                    'title'=> "Editar Incidencias #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else{ 
                if ($model->load($request->post())) {
                    $uploadedFile = UploadedFile::getInstance($model, 'imagen');
                    if($uploadedFile){
                        $model->imagen = $uploadedFile->baseName. '.' . $uploadedFile->extension;
                    }elseif(!empty($_POST["imgUpdate"])){
                        $model->imagen = $_POST["imgUpdate"];
                    }
                    if ($model->validate()) {
                        if(isset($_FILES["Incidencias"]["name"]["imagen"]) && !empty($_FILES["Incidencias"]["name"]["imagen"])){
                            Yii::$app->params['uploadPath']= Yii::$app->basePath.'/web/uploads/';
                            $uploadedFile->saveAs(Yii::$app->params['uploadPath'] . $uploadedFile->baseName. '.' . $uploadedFile->extension);
                        }
                        if($model->save()){
                            return [
                                'forceReload'=>'#crud-datatable-incidencias-pjax',
                                'title'=> "Incidencias #".$id,
                                'content'=>$this->renderAjax('view', [
                                    'model' => $model,
                                ]),
                                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                        Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                            ]; 
                        }              
                    }
                     return [
                        'title'=> "Editar Incidencias #".$id,
                        'content'=>$this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                    ]; 
                }
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())) {
                $uploadedFile = UploadedFile::getInstance($model, 'imagen');
                if($uploadedFile){
                    $model->imagen = $uploadedFile->baseName. '.' . $uploadedFile->extension;
                }elseif(!empty($_POST["imgUpdate"])){
                    $model->imagen = $_POST["imgUpdate"];
                }
                if ($model->validate()) {
                    if(isset($_FILES["Incidencias"]["name"]["imagen"]) && !empty($_FILES["Incidencias"]["name"]["imagen"])){
                        Yii::$app->params['uploadPath']= Yii::$app->basePath.'/web/uploads/';
                        $uploadedFile->saveAs(Yii::$app->params['uploadPath'] . $uploadedFile->baseName. '.' . $uploadedFile->extension);
                    }
                    if($model->save()){
                        return $this->redirect(['view', 'id' => $model->id_incidencia]);
                    }              
                }
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Delete an existing Incidencias model.
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-incidencias-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

     /**
     * Delete multiple existing Incidencias model.
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-incidencias-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the Incidencias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Incidencias the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Incidencias::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBandejaincidencias()
    {
        $request = Yii::$app->request;
        $searchModelIncidencias = new IncidenciasSearch();
        $dataProviderIncidencias = $searchModelIncidencias->search(Yii::$app->request->queryParams);
        if(Yii::$app->user->getId()!==null){
            $usuario = User::findIdentity(Yii::$app->user->getId());
            $Personas = Personas::findOne(['cedula'=>$usuario->cedula, 'estatus' => true]);
        }

        return $this->render('bandejaincidencias', [
            'searchModelIncidencias' => $searchModelIncidencias,
            'dataProviderIncidencias' => $dataProviderIncidencias,
            'Personas' => $Personas,
        ]);     
    }
}
