<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CategoriaIncidencia */
?>
<div class="categoria-incidencia-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_categoria_incidencia',
            'nombre_categoria_incidencia',
            'ip_log:ntext',
            'usuario_creador',
            'usuario_modificador',
            'fecha_creacion',
            'fecha_modificacion',
            'estatus:boolean',
        ],
    ]) ?>

</div>
