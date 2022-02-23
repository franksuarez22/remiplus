<?php
use yii\helpers\Url;
use yii\bootstrap4\Html;
use mdm\admin\components\Helper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id_incidencia',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_categoria_incidencia',
        'value'=>'categoria.nombre_categoria_incidencia',
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(app\models\Categoriaincidencia::find(['estatus' => true])->all(),'id_categoria_incidencia','nombre_categoria_incidencia'),
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
        'filterInputOptions'=>['placeholder'=>''],
        'format'=>'raw'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_tipo_incidencia',
        'value'=>'tipoincidencia.nombre_tipo_incidencia',
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(app\models\Tipoincidencia::find()->where(['estatus' => true])->all(),'id_tipo_incidencia','nombre_tipo_incidencia'),
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
        'filterInputOptions'=>['placeholder'=>''],
        'format'=>'raw'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_parroquia',
        'value'=>'parroquia.parroquia',
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(app\models\Parroquias::find()->where(['id_municipio' => $Personas->id_municipio,'estatus' => true])->all(),'id_parroquia','parroquia'),
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
        'filterInputOptions'=>['placeholder'=>''],
        'format'=>'raw'
    ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'id_ciudad',
    //     'value'=>'ciudad.ciudad'
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
    ],
    /*[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'direccion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'punto_referencia',
    ],*/
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'latitud',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'longitud',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'imagen',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'ip_log',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'usuario_creador',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'usuario_modificador',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fecha_creacion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fecha_modificacion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'estatus',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'template' => Helper::filterActionColumn('{view} {update} {delete}'),
        'visibleButtons' => [
            'view' => function ($model) {
                return Helper::checkRoute('/incidencias/view');
            },
            'update' => function ($model) {
                return Helper::checkRoute('/incidencias/update');
            },
            'delete' => function ($model) {
                return Helper::checkRoute('/incidencias/delete');
            },
        ],
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to(['/incidencias/'.$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Borrar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'¿Está seguro?',
                          'data-confirm-message'=>'¿Está seguro que desea borrar este registro?'], 
    ],

];   
