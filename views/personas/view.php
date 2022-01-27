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
            'id_genero',
            'id_nacionalidad',
            'id_parroquia',
            'id_ciudad',
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
