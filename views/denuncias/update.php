<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Denuncias */
?>
<div class="denuncias-update">

    <?= $this->render('_form', [
        'model' => $model,
        'Denunciasevidencias' => $Denunciasevidencias,
        'pks' => $pks,
        'evidencias' => $evidencias
    ]) ?>

</div>
