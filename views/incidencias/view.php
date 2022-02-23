<?php

use yii\widgets\DetailView;
use yii\bootstrap4\Html;
use app\components\ComplementFunctions as cf;
use app\assets\MapAsset;
use  yii\web\View; 
/* @var $this yii\web\View */
/* @var $model app\models\Incidencias */

MapAsset::register($this);

$this->registerJsFile('@web/js/map/mapa_view.js',[
    'depends'=>[app\assets\MapAsset::className()]
]);

$this->registerJs(
    "initMapView();", View::POS_READY,  'cargaMapaView'
);

$rol = cf::rolUsuario(Yii::$app->user->getId());
?>
<div class="incidencias-view">

    <table border="1" class="table table-striped table-bordered detail-view">
        <tr>
            <td><b><?=$model->getAttributeLabel("id_categoria_incidencia")?>:</b></td>
            <td><?=$model->categoria->nombre_categoria_incidencia?></td>
            <td><b><?=$model->getAttributeLabel("id_tipo_incidencia")?>:</b></td>
            <td><?= $model->tipoincidencia->nombre_tipo_incidencia?></td>
        </tr>
        <tr>
            <td><b><?=$model->getAttributeLabel("descripcion")?>:</b></td>
            <td colspan="3"><?=$model->descripcion?></td>
        </tr>
        <tr>
            <td><b><?=$model->getAttributeLabel("id_estado")?>:</b></td>
            <td><?=$model->estado->estado?></td>
            <td><b><?=$model->getAttributeLabel("id_municipio")?>:</b></td>
            <td><?=$model->municipio->municipio?></td>
        </tr>
        <tr>
            <td><b><?=$model->getAttributeLabel("id_parroquia")?>:</b></td>
            <td><?=$model->parroquia->parroquia?></td>
            <td><b><?=$model->getAttributeLabel("id_ciudad")?>:</b></td>
            <td><?=$model->ciudad->ciudad?></td>
        </tr>
        <tr>
            <td><b><?=$model->getAttributeLabel("direccion")?>:</b></td>
            <td colspan="3"><?=$model->direccion?></td>
        </tr>
        <tr>
            <td><b><?=$model->getAttributeLabel("punto_referencia")?>:</b></td>
            <td colspan="3"><?=$model->punto_referencia?></td>
        </tr>
        <tr>
            <td><b><?=$model->getAttributeLabel("latitud")?>:</b><input type="hidden" id="latitud" value="<?=$model->latitud?>"></td>
            <td><?=$model->latitud?></td>
            <td><b><?=$model->getAttributeLabel("longitud")?>:</b><input type="hidden" id="longitud" value="<?=$model->longitud?>"></td>
            <td><?=$model->longitud?></td>
        </tr>
        <tr>
            <td colspan="4"><div id="map"></div></td>
        </tr>
        <tr>
            <td colspan="4"><b><?=$model->getAttributeLabel("imagen")?>:</b></td>
        </tr>
        <tr>
            <td colspan="4"><?= $model->imagen!=''? Html::img('@web/uploads/'.$model->imagen, ['class'=>"img-responsive",'style'=>"margin:auto;"]):'Sin regístro'; ?></td>
        </tr>
    </table>
    <?php if(in_array('Administrador', $rol)){ ?>
    <table border="1" class="table table-striped table-bordered detail-view">
        <tr>
            <td colspan="4"><b><?=$model->getAttributeLabel("ip_log")?>:</b></td>
        </tr>
        <tr>
            <td colspan="4"><?=$model->ip_log?></td>
        </tr>
        <tr>
            <td><b><?=$model->getAttributeLabel("usuario_creador")?>:</b></td>
            <td><?=$model->usuario_creador?></td>
            <td><b><?=$model->getAttributeLabel("usuario_modificador")?>:</b></td>
            <td><?=$model->usuario_modificador?></td>
        </tr>
        <tr>
            <td><b><?=$model->getAttributeLabel("fecha_creacion")?>:</b></td>
            <td><?=$model->fecha_creacion?></td>
            <td><b><?=$model->getAttributeLabel("fecha_modificacion")?>:</b></td>
            <td><?=$model->fecha_modificacion?></td>
        </tr>
        <tr>
            <td><b><?=$model->getAttributeLabel("estatus")?>:</b></td>
            <td><?=$model->estatus==1?'Activo':'Inactivo';?></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <?php } ?>
</div>
