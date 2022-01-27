<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Denuncias */
?>
<div class="denuncias-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_denuncia',
            'id_tipo_incidencia',
            'id_parroquia',
            'id_ciudad',
            'descripcion:ntext',
            'direccion:ntext',
            'punto_referencia',
            'latitud',
            'longitud',
            'ip_log:ntext',
            'usuario_creador',
            'usuario_modificador',
            'fecha_creacion',
            'fecha_modificacion',
            'estatus:boolean',
        ],
    ]) ?>

</div>
