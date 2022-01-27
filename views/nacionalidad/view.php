<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Nacionalidad */
?>
<div class="nacionalidad-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_nacionalidad',
            'descripcion_nacionalidad',
            'ip_log:ntext',
            'usuario_creador',
            'usuario_modificador',
            'fecha_creacion',
            'fecha_modificacion',
            'estatus:boolean',
        ],
    ]) ?>

</div>
