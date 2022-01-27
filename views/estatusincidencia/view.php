<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Estatusincidencia */
?>
<div class="estatusincidencia-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_estatus_incidencia',
            'estatus_incidencia',
            'ip_log:ntext',
            'usuario_creador',
            'usuario_modificador',
            'fecha_creacion',
            'fecha_modificacion',
            'estatus:boolean',
        ],
    ]) ?>

</div>
