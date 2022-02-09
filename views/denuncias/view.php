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
            [
            'attribute'=>'id_tipo_incidencia',
            'value'=>$model->tipoincidencia->nombre_tipo_incidencia           
            ],
            [
            'attribute'=>'id_estado',
            'value'=>$model->estado->estado
            ],
            [
            'attribute'=>'id_municipio',
            'value'=>$model->municipio->municipio
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
            'ip_log:ntext',
            'usuario_creador',
            'usuario_modificador',
            'fecha_creacion',
            'fecha_modificacion',
            'estatus:boolean',
        ],
    ]) ?>

</div>
