<?php

namespace app\controllers;

use Yii;
use app\components\ComplementFunctions as cf;
use app\models\Denuncias;
use app\models\DenunciasSearch;
use app\models\Incidencias;
use app\models\IncidenciasSearch;
use app\models\Denunciaspersonas;
use app\models\Incidenciasdenuncias;
use app\models\Denunciasevidencias;
use app\models\Incidenciasestatus;
use app\models\Personas;
use mdm\admin\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use mdm\admin\components\Helper;
use yii\web\UploadedFile;
/**
 * DenunciasController implements the CRUD actions for Denuncias model.
 */
class DenunciasController extends Controller
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
     * Lists all Denuncias models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new DenunciasSearch();
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


    /**
     * Displays a single Denuncias model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        $Denunciasevidencias = new Denunciasevidencias();
        $evidencias = Denunciasevidencias::find()->where(['id_denuncia' => $id])->all();
        $Incidenciasdenuncias = Incidenciasdenuncias::find()->where(['id_denuncia' => $id])->all();
        foreach ($Incidenciasdenuncias as $key => $value) {
            $pieces[]=$value->id_incidencia;
        }
        $pks = implode(',', $pieces);
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $btnEditar = '';
            if(Helper::checkRoute('update')){
              $btnEditar = Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote']);
            }
            return [
                    'title'=> "Denuncias #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                        'evidencias' => $evidencias,
                        'Denunciasevidencias' => $Denunciasevidencias,
                        'pks' => $pks,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]). $btnEditar
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
                'evidencias' => $evidencias,
                'Denunciasevidencias' => $Denunciasevidencias,
                'pks' => $pks,
            ]);
        }
    }

    /**
     * Creates a new Denuncias model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Denuncias(); 
        $Denunciaspersonas = new Denunciaspersonas();
        $Denunciasevidencias = new Denunciasevidencias();
        $Incidenciasestatus = new Incidenciasestatus();
        if(Yii::$app->user->getId()!==null){
            $usuario = User::findIdentity(Yii::$app->user->getId());
            $personas = Personas::findOne(['cedula'=>$usuario->cedula, 'estatus' => true]);

            $model->id_estado=$personas->id_estado;
            $model->id_municipio=$personas->id_municipio;
            $model->id_parroquia=$personas->id_parroquia;
            $model->id_ciudad=$personas->id_ciudad;
        } 
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                if(empty($request->get( 'pks' )) || is_null($request->get( 'pks' ))){
                    return [
                        'title'=> "Denuncias",
                        'content'=>'<span class="text-danger">Seleccione las incidencias relacionadas a la denuncia a realizar</span>',
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
            
                    ];        
                }    
                return [
                    'title'=> "Denuncias",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                        'Denunciasevidencias' => $Denunciasevidencias,
                        'pks' => $pks,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else{
                extract($_POST);
                //cf::iam([$_FILES],true);
                if(empty($pks) || is_null($pks)){
                    return [
                        'title'=> "Denuncias",
                        'content'=>'<span class="text-danger">Seleccione las incidencias relacionadas a la denuncia a realizar</span>',
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
            
                    ];        
                }
                //die($pks);  
                if($model->load($request->post())){
                    //$Denunciasevidencias->load($request->post());
                    $valido = true;
                    $valido = $model->validate() && $valido;
                    //$valido = $Denunciasevidencias->validate() && $valido;
                    //$valido = $pks.lenght()>=10 && $valido;
                    if($valido){
                        $transaction = $model::getDb()->beginTransaction();
                        try {
                            $guardado = true;
                            $guardado = $model->save(false) && $guardado;
                            $ultimoIdDenuncia=$model->primaryKey;


                            $total_img = count($_FILES["Denunciasevidencias"]["name"]["evidencia"]);
                            if($total_img>0){
                                //recorre el arreglo para guardar imagenes
                                for ($i = 0; $i < $total_img; $i++){
                                    if(!empty($_FILES['Denunciasevidencias']["name"]["evidencia"][$i])) {
                                        $Denunciasevidencias[$i] = new Denunciasevidencias();
                                        $uploadedFile[$i]=UploadedFile::getInstance($Denunciasevidencias[$i],'evidencia[' . $i . ']');
                                        if($uploadedFile){                    
                                            $Denunciasevidencias[$i]->id_denuncia = $ultimoIdDenuncia;
                                            $Denunciasevidencias[$i]->evidencia = $uploadedFile[$i]->baseName. '.' . $uploadedFile[$i]->extension;
                                            $Denunciasevidencias[$i]->validate();
                                            $guardado = $Denunciasevidencias[$i]->save(false) && $guardado;
                                            Yii::$app->params['uploadPath']= Yii::$app->basePath.'/web/uploads/';
                                            $uploadedFile[$i]->saveAs(Yii::$app->params['uploadPath'] . $uploadedFile[$i]->baseName. '.' . $uploadedFile[$i]->extension);
                                        }
                                    }
                                }
                            }
                            $pks = explode(',', $pks);
                            foreach ( $pks as $pk ) {
                                $Incidenciasdenuncias = new Incidenciasdenuncias();
                                $Incidenciasdenuncias->id_incidencia = $pk;
                                $Incidenciasdenuncias->id_denuncia = $ultimoIdDenuncia;
                                $Incidenciasdenuncias->validate();
                                $guardado = $Incidenciasdenuncias->save(false) && $guardado;
                            }

                            $Denunciaspersonas->id_denuncia = $ultimoIdDenuncia;
                            $Denunciaspersonas->id_persona = $personas->id_persona;
                            $Denunciaspersonas->validate();
                            $guardado = $Denunciaspersonas->save(false) && $guardado;

                            $Incidenciasestatus->id_denuncia = $ultimoIdDenuncia;
                            $Incidenciasestatus->id_estatus_incidencia = 1;//Abierta
                            $Incidenciasestatus->fecha_cambio_estatus = date('Y-m-d');
                            $Incidenciasestatus->observaciones = 'Denuncia Abierta';
                            $Incidenciasestatus->validate();
                            $guardado = $Incidenciasestatus->save(false) && $guardado;

                            if($guardado){
                                $transaction->commit();
                                return [
                                    'forceReload'=>'#crud-datatable-denuncias-pjax',
                                    'title'=> "Denuncias",
                                    'content'=>'<span class="text-success">Datos Guardados</span>',
                                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                            Html::a('Guardar Nuevo',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                        
                                ];
                            }   
                        } catch(\Exception $e) {
                            $transaction->rollBack();
                            throw $e;
                                return [
                                    'title'=> "Denuncias",
                                    'content'=>'<span class="text-danger">Datos No Guardados</span>',
                                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                        
                                ]; 
                        }
                    }
                }          
                return [
                    'title'=> "Denuncias",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                        'Denunciasevidencias' => $Denunciasevidencias,
                        'pks' => $pks,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            /*if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id_denuncia]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }*/
            return $this->redirect(['/denuncias/', 'id' => $model->id_denuncia]);
        }
       
    }

    /**
     * Updates an existing Denuncias model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $pieces=[];
        $model = $this->findModel($id); 
        $Denunciasevidencias = new Denunciasevidencias();      
        $evidencias = Denunciasevidencias::find()->where(['id_denuncia' => $id])->all();
        $Incidenciasdenuncias = Incidenciasdenuncias::find()->where(['id_denuncia' => $id])->all();
        foreach ($Incidenciasdenuncias as $key => $value) {
            $pieces[]=$value->id_incidencia;
        }
        $pks = implode(',', $pieces);
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Editar Denuncias #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                        'Denunciasevidencias' => $Denunciasevidencias,
                        'pks' => $pks,
                        'evidencias' => $evidencias
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else{
                if($model->load($request->post())){

                    if ($model->validate()) {
                        $transaction = $model::getDb()->beginTransaction();
                        try {
                            $guardado = true;
                            $guardado = $model->save(false) && $guardado;
                            $ultimoIdDenuncia=$model->primaryKey;

                            $total_img = count($_FILES["Denunciasevidencias"]["name"]["evidencia"]);
                            if($total_img>0){
                                //recorre el arreglo para guardar imagenes
                                for ($i = 0; $i < $total_img; $i++){
                                    if(!empty($_FILES['Denunciasevidencias']["name"]["evidencia"][$i])) {
                                        $Denunciasevidencia[$i] = new Denunciasevidencias();
                                        $uploadedFile[$i]=UploadedFile::getInstance($Denunciasevidencia[$i],'evidencia[' . $i . ']');
                                        if($uploadedFile){                    
                                            $Denunciasevidencia[$i]->id_denuncia = $ultimoIdDenuncia;
                                            $Denunciasevidencia[$i]->evidencia = $uploadedFile[$i]->baseName. '.' . $uploadedFile[$i]->extension;
                                            $Denunciasevidencia[$i]->validate();
                                            $guardado = $Denunciasevidencia[$i]->save(false) && $guardado;
                                            Yii::$app->params['uploadPath']= Yii::$app->basePath.'/web/uploads/';
                                            $uploadedFile[$i]->saveAs(Yii::$app->params['uploadPath'] . $uploadedFile[$i]->baseName. '.' . $uploadedFile[$i]->extension);
                                        }
                                    }
                                }
                            }
                            if($guardado){
                                $transaction->commit();
                                return [
                                    'forceReload'=>'#crud-datatable-denuncias-pjax',
                                    'title'=> "Denuncias #".$id,
                                    'content'=>$this->renderAjax('view', [
                                        'model' => $model,
                                        'Denunciasevidencias' => $Denunciasevidencias,
                                        'pks' => $pks,
                                        'evidencias' => $evidencias
                                    ]),
                                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                                ];
                            }
                        } catch(\Exception $e) {
                            $transaction->rollBack();
                            throw $e;
                        }
                            
                    }
                     return [
                        'title'=> "Editar Denuncias #".$id,
                        'content'=>$this->renderAjax('update', [
                            'model' => $model,
                            'Denunciasevidencias' => $Denunciasevidencias,
                            'pks' => $pks,
                            'evidencias' => $evidencias
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
            /*if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id_denuncia]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }*/
            return $this->redirect(['/denuncias/', 'id' => $model->id_denuncia]);
        }
    }

    /**
     * Delete an existing Denuncias model.
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-denuncias-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

     /**
     * Delete multiple existing Denuncias model.
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-denuncias-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the Denuncias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Denuncias the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Denuncias::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBandejadenuncias()
    {
        $request = Yii::$app->request;
        $searchModelIncidencias = new IncidenciasSearch();
        $dataProviderIncidencias = $searchModelIncidencias->search(Yii::$app->request->queryParams);

        $searchModelDenuncias = new DenunciasSearch();
        $dataProviderDenuncias = $searchModelDenuncias->search(Yii::$app->request->queryParams);
        if(Yii::$app->user->getId()!==null){
            $usuario = User::findIdentity(Yii::$app->user->getId());
            $Personas = Personas::findOne(['cedula'=>$usuario->cedula, 'estatus' => true]);
        }

        return $this->render('bandejadenuncias', [
            'searchModelIncidencias' => $searchModelIncidencias,
            'dataProviderIncidencias' => $dataProviderIncidencias,
            'searchModelDenuncias' => $searchModelDenuncias,
            'dataProviderDenuncias' => $dataProviderDenuncias,
            'Personas' => $Personas,
        ]);     
    }
}
