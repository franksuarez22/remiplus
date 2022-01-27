<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Parroquias */
?>
<div class="parroquias-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_parroquia',
            'id_municipio',
            'parroquia',
            'ip_log:ntext',
            'usuario_creador',
            'usuario_modificador',
            'fecha_creacion',
            'fecha_modificacion',
            'estatus:boolean',
        ],
    ]) ?>

</div>
