<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Municipios */
?>
<div class="municipios-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_municipio',
            'id_estado',
            'municipio',
            'ip_log:ntext',
            'usuario_creador',
            'usuario_modificador',
            'fecha_creacion',
            'fecha_modificacion',
            'estatus:boolean',
        ],
    ]) ?>

</div>
