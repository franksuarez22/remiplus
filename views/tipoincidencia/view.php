<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Tipoincidencia */
?>
<div class="tipoincidencia-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_tipo_incidencia',
            'id_categoria_incidencia',
            'nombre_tipo_incidencia',
            'ip_log:ntext',
            'usuario_creador',
            'usuario_modificador',
            'fecha_creacion',
            'fecha_modificacion',
            'estatus:boolean',
        ],
    ]) ?>

</div>
