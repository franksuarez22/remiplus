<?php
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Personas */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="personas-form">

    <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'options' => [
                'enctype' => 'multipart/form-data'       
            ]
        ]); ?>
<div class="form-row">
    <div class='col-md-4'>
        <?= $form->field($model, 'id_genero')->textInput() ?>
    </div>
<div class='col-md-4'>
        <?= $form->field($model, 'id_nacionalidad')->textInput() ?>
    </div>
<div class='col-md-4'>
        <?= $form->field($model, 'id_parroquia')->textInput() ?>
    </div>
<div class='col-md-4'>
        <?= $form->field($model, 'id_ciudad')->textInput() ?>
    </div>
<div class='col-md-4'>
        <?= $form->field($model, 'cedula')->textInput() ?>
    </div>
<div class='col-md-4'>
        <?= $form->field($model, 'primer_nombre')->textInput(['maxlength' => true]) ?>
    </div>
<div class='col-md-4'>
        <?= $form->field($model, 'segundo_nombre')->textInput(['maxlength' => true]) ?>
    </div>
<div class='col-md-4'>
        <?= $form->field($model, 'primer_apellido')->textInput(['maxlength' => true]) ?>
    </div>
<div class='col-md-4'>
        <?= $form->field($model, 'segundo_apellido')->textInput(['maxlength' => true]) ?>
    </div>
<div class='col-md-4'>
        <?= $form->field($model, 'fecha_nacimiento')->textInput() ?>
    </div>
<div class='col-md-4'>
        <?= $form->field($model, 'telefono_contacto')->textInput(['maxlength' => true]) ?>
    </div>
<div class='col-md-4'>
        <?= $form->field($model, 'correo_electronico')->textInput(['maxlength' => true]) ?>
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
