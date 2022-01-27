<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Incidencias */
?>
<div class="incidencias-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_incidencia',
            'id_tipo_incidencia',
            'id_parroquia',
            'id_ciudad',
            'descripcion:ntext',
            'direccion:ntext',
            'punto_referencia',
            'latitud',
            'longitud',
            'imagen',
            'ip_log:ntext',
            'usuario_creador',
            'usuario_modificador',
            'fecha_creacion',
            'fecha_modificacion',
            'estatus:boolean',
        ],
    ]) ?>

</div>
