<?php

/* @var $this yii\web\View */
use yii\bootstrap4\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-5">Sistema de gestión, seguimiento y control gubernamental</h1>

        <!--<p class="lead">Red de Reportes de Incidencias para la Movilización y Respuesta Inmediata.</p>-->

        <p><a class="btn btn-lg btn-success" href="<?php echo Yii::$app->homeUrl . "admin/user/logindenunciante"; ?>">Reportar una incidencia</a></p>
    </div>

    <div class="body-content text-center">
        <?= Html::img('@web/img/mincyt.png', ['alt' => '']) ?>
    </div>
</div>
