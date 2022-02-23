<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\captcha\Captcha;
use rmrevin\yii\fontawesome\FAS;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Login */

//$this->title = Yii::t('rbac-admin', 'Login');
$this->title = 'Iniciar Sesión';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Por favor llene los siguientes campos para iniciar sesión:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'username')->textInput()->label('Usuario') ?>
                <?= $form->field($model, 'password')->passwordInput()->label('Clave') ?>
                <?= $form->field($model, 'rememberMe')->checkbox()->label('Recordarme') ?>
                <div style="color:#999;margin:1em 0">
                    si perdió su clave puede <?= Html::a('resetearlo', ['user/request-password-reset']) ?>.
                    <!--Para nuevo usuario usted puede--> <?//= Html::a('registrarse', ['user/signup']) ?>.
                </div>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('rbac-admin', 'Ingresar'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
