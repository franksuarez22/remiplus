<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user app\models\Usuario */
?>
<div class="verify-email">
    <p>Estimado(s) <?= Html::encode($model->cedula) ?>,</p>

    <p>Sus credenciales de ingreso son:</p>
    <p>Usuario: <?= $model->cedula ?></p>
    <p>token: <?= $model->token_sesion ?></p>

    <p>Tome las medidas para resguardar su información de ingreso.</p>
</div>
