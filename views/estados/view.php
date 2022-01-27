<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Estados */
?>
<div class="estados-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_estado',
            'estado',
            'ip_log:ntext',
            'usuario_creador',
            'usuario_modificador',
            'fecha_creacion',
            'fecha_modificacion',
            'estatus:boolean',
        ],
    ]) ?>

</div>
