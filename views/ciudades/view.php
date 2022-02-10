<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Ciudades */
?>
<div class="ciudades-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_ciudad',
            'id_parroquia',
            'ciudad',
            'ip_log:ntext',
            'usuario_creador',
            'usuario_modificador',
            'fecha_creacion',
            'fecha_modificacion',
            'estatus:boolean',
        ],
    ]) ?>

</div>
