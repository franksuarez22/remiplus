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
            
           [
            'attribute'=>'id_tipo_incidencia',
            'value'=>$model->tipoincidencia->nombre_tipo_incidencia           
           ],
           [
               'attribute'=>'id_parroquia',
               'value'=>$model->parroquia->parroquia
           ],
           [
               'attribute'=>'id_ciudad',
               'value'=>$model->ciudad->ciudad
           ],
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
