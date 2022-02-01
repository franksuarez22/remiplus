<?php
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Ciudades */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="ciudades-form">

    <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'options' => [
                'enctype' => 'multipart/form-data'       
            ]
        ]); ?>
<div class="form-row">
    <div class='col-md-4'>
        <?= $form->field($model, 'id_parroquia')->textInput() ?>
    </div>
<div class='col-md-4'>
        <?= $form->field($model, 'ciudad')->textInput(['maxlength' => true]) ?>
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
