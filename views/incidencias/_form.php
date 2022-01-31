<?php
use yii\helpers\Url;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use app\assets\MapAsset;
/* @var $this yii\web\View */
/* @var $model app\models\Incidencias */
/* @var $form yii\bootstrap4\ActiveForm */

MapAsset::register($this);
?>

<div class="incidencias-form">

    <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'options' => [
                'enctype' => 'multipart/form-data'       
            ]
        ]); ?>
<div class="form-row">
    <div class='col-md-6'>
        <?php 
            $tipo_incidencia = app\models\Tipoincidencia::find(['estatus' => true])->all();
            $lista_tipo_incidencia = ArrayHelper::map($tipo_incidencia,'id_tipo_incidencia','nombre_tipo_incidencia');
            echo $form->field($model, 'id_tipo_incidencia')->widget(Select2::classname(), [
                'data' => $lista_tipo_incidencia,
                'options' => [
                    'placeholder' => 'Seleccione...',
                    'id'=>'tipo_incidencia',
                    'class'=>'select2'
                    ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
        ?>
    </div>
    <div class='col-md-6'>
            <?php 
                $estados = app\models\Estados::find(['estatus' => true])->all();
                $listaestados = ArrayHelper::map($estados,'id_estado','estado');
                echo $form->field($model, 'id_estado')->widget(Select2::classname(), [
                    'data' => $listaestados,    
                    'options' => ['placeholder' => 'Seleccione','id'=>'estado'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);            
            ?>
    </div>
    <div class='col-md-6'>
        <?php 
            if(!empty($model->id_estado)){
                $Municipios = app\models\Municipios::find()->where(['id_estado' => $model->id_estado, 'estatus' => true])->all();
                $listaMunicipios = ArrayHelper::map($Municipios,'id_municipio','municipio');
            }else{
                $listaMunicipios = [];
                $model->id_municipio = '';
            } 
            echo $form->field($model, 'id_municipio')->widget(DepDrop::classname(), [
                'data' => $listaMunicipios,
                'type' => DepDrop::TYPE_SELECT2,
                'options'=>['id_municipio'=>'municipio','id'=>'municipio'],
                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                'pluginOptions'=>[
                    'depends'=>['estado'],
                    'placeholder'=>'Seleccione',
                    'url'=>Url::to(['/municipios/municipios'])
                ]
            ]);
        ?>        
    </div>
    <div class='col-md-6'>
        <?php 
            if(!empty($model->id_municipio)){
                $Parroquias = app\models\Parroquias::find()->where(['id_municipio' => $model->id_municipio, 'estatus' => true])->all();
                $listaParroquias = ArrayHelper::map($Parroquias,'id_parroquia','parroquia');
            }else{
                $listaParroquias = [];
                $model->id_parroquia = '';
            }  
            echo $form->field($model, 'id_parroquia')->widget(DepDrop::classname(), [
                'data' => $listaParroquias,
                'type' => DepDrop::TYPE_SELECT2,
                'options'=>['id_parroquia'=>'parroquia','id'=>'parroquia'],
                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                'pluginOptions'=>[
                    'depends'=>['municipio'],
                    'placeholder'=>'Seleccione',
                    'url'=>Url::to(['/parroquias/parroquias'])
                ]
            ]);        
        ?>
    </div>
    <div class='col-md-6'>
        <?php 
            if(!empty($model->id_parroquia)){
                $Ciudades = app\models\Ciudades::find()->where(['id_parroquia' => $model->id_parroquia, 'estatus' => true])->all();
                $listaCiudades = ArrayHelper::map($Ciudades,'id_ciudad','descripcion');
            }else{
                $listaCiudades = [];
                $model->id_ciudad = '';
            }  
            echo $form->field($model, 'id_ciudad')->widget(DepDrop::classname(), [
                'data' => $listaCiudades,
                'type' => DepDrop::TYPE_SELECT2,
                'options'=>['id_ciudad'=>'ciudad','id'=>'ciudad'],
                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                'pluginOptions'=>[
                    'depends'=>['parroquia'],
                    'placeholder'=>'Seleccione',
                    'url'=>Url::to(['/ciudades/ciudades'])
                ]
            ]);         
        ?>
    </div>
    <div class='col-md-12'>
        <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>
    </div>
    <div class='col-md-6'>
        <?= $form->field($model, 'latitud')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'longitud')->hiddenInput()->label(false) ?>
    </div>
    <div class='col-md-12'>
        <div id="map"></div>
    </div><br><br>
    <div class='col-md-12'>
        <?= $form->field($model, 'direccion')->textarea(['rows' => 6]) ?>
    </div>
    <div class='col-md-12'>
        <?= $form->field($model, 'punto_referencia')->textInput(['maxlength' => true]) ?>
    </div>
    <div class='col-md-12'>
        <?= $form->field($model, 'imagen')->textInput(['maxlength' => true]) ?>
    </div>
    <?php /**/if(!$model->isNewRecord){/**/ ?>
        <div class='col-md-4'>
              <?= $form->field($model, "estatus")->checkbox() ?>
        </div>
    <?php } ?>  
</div>    
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Guardar') : Yii::t('app', 'Editar'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
