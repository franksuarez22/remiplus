<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Personas */
?>
<div class="personas-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_persona',
             [
                'attribute'=>'id_genero',
                'value'=>$model->genero->descripcion_genero
            ],
            [
                'attribute'=>'id_nacionalidad',
                'value'=>$model->nacionalidad->descripcion_nacionalidad
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
            'cedula',
            'primer_nombre',
            'segundo_nombre',
            'primer_apellido',
            'segundo_apellido',
            'fecha_nacimiento',
            'telefono_contacto',
            'correo_electronico',
            'ip_log:ntext',
            'usuario_creador',
            'usuario_modificador',
            'fecha_creacion',
            'fecha_modificacion',
            'estatus:boolean',
        ],
    ]) ?>

</div>
