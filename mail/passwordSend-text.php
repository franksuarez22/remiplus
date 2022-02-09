<?php
/* @var $this yii\web\View */
/* @var $user app\models\Usuario */
?>
Estimado <?= $model->cedula ?>,

Sus credenciales de ingreso son:
Usuario: <?= $model->cedula ?>
Clave: <?= $model->token_sesion ?>

Tome las medidas para resguardar su información de ingreso.