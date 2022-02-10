<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

$this->title = 'Inicio de sesión';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Por favor llene los siguientes campos para iniciar sesión:</p>

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => true,
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-2 col-form-label mr-lg-3'],
            'inputOptions' => ['class' => 'col-lg-3 form-control'],
            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
        ],
    ]); ?>
        <?php 
            $Nacionalidad = app\models\Nacionalidad::find(['estatus' => true])->orderBy('id_nacionalidad')->all();
            $listaNacionalidad = ArrayHelper::map($Nacionalidad,'id_nacionalidad','descripcion_nacionalidad');
            echo $form->field($model, 'id_nacionalidad')->dropDownList($listaNacionalidad, []);
        ?>
        <?php 

           /* echo $form->field($model, 'id_nacionalidad')->widget(Select2::classname(), [
                'data' => $listaNacionalidad,               
                'options' => [
                    'placeholder' => 'Seleccione...',
                    ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);*/
        ?>
        <?= $form->field($model, 'cedula')->textInput(['autofocus' => true, 'readOnly' =>$input_token]) ?>

        <?= $form->field($model, 'telefono')->textInput(['readOnly' =>$input_token]) ?>

        <?= $form->field($model, 'correo')->textInput(['readOnly' =>$input_token]) ?>

        <?php
            if($input_token==true){
                //echo $form->field($model, 'token')->textInput();
                $btn = '';
                if($rtoken){
                    $btn ='<button class="btn btn-outline-primary" type="button" id="reenviar"><i class="fas fa-sync"></i></button>';
                }
                echo $form->field($model, 'token', [
                    'template' => '{beginLabel}{labelTitle}{endLabel}{input}'.$btn.'
                    {error}{hint}'
                ])->textInput(['aria-describedby'=>'reenviar']);
                echo $token;
            }
        ?>

        <?php /*$form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"offset-lg-1 col-lg-3 custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ])*/ ?>

        <div class="form-group">
            <div class="offset-lg-1 col-lg-11">
                <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    <!--<div class="offset-lg-1" style="color:#999;">
        You may login with <strong>admin/admin</strong> or <strong>demo/demo</strong>.<br>
        To modify the username/password, please check out the code <code>app\models\User::$users</code>.
    </div>-->
</div>
