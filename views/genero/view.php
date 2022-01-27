<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Genero */
?>
<div class="genero-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_genero',
            'descripcion_genero',
            'ip_log:ntext',
            'usuario_creador',
            'usuario_modificador',
            'fecha_creacion',
            'fecha_modificacion',
            'estatus:boolean',
        ],
    ]) ?>

</div>
