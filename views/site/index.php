<?php

/* @var $this yii\web\View */
use yii\bootstrap4\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Sistema de Reportes de Incidencias</h1>

        <p class="lead">Red de Reportes de Incidencias para la Movilización y Respuesta Inmediata.</p>

        <p><a class="btn btn-lg btn-success" href="<?php echo Yii::$app->homeUrl . "site/logindenunciante"; ?>">Reportar una incidencia</a></p>
    </div>

    <div class="body-content text-center">
        <?= Html::img('@web/img/fondo_institucional.png', ['alt' => '', 'style' => 'width:900px;height: 300px']) ?>
    </div>
</div>
