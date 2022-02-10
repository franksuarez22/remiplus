<?php
use yii\helpers\Url;
use yii\bootstrap4\Html;
use yii\bootstrap4\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TipoincidenciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tipoincidencias');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$nuevo=$exportar='';
    if(Helper::checkRoute('create')){
       $nuevo = Html::a('<i class="glyphicon glyphicon-plus"></i>', ['/tipoincidencia/create'],
                ['role'=>'modal-remote','title'=> 'Nuevo Tipoincidencias','class'=>'btn btn-default']);
    }

    if(Helper::checkRoute('gridview/export/download')){
       $exportar = '{export}';
    }
?>
<div class="tipoincidencia-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable-tipoincidencia',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    $nuevo.
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', Url::to(''),
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Actualizar']).
                    '{toggleData}'.
                    $exportar
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'primary', 
                'heading' => '<i class="glyphicon glyphicon-list"></i> Listado de Tipoincidencias',
                'before'=>'<em>* Cambie el tamaño de las columnas de la tabla como una hoja de cálculo arrastrando los bordes de la columna.</em>',
                'after'=>Helper::checkRoute('bulk-delete') ? BulkButtonWidget::widget([
                            'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Borrar Todo',
                                ["bulk-delete"] ,
                                [
                                    "class"=>"btn btn-danger btn-xs",
                                    'role'=>'modal-remote-bulk',
                                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                    'data-request-method'=>'post',
                                    'data-confirm-title'=>'¿Está seguro?',
                                    'data-confirm-message'=>'¿Está seguro que desea borrar los registros seleccionados?'
                                ]),
                        ]).                        
                        '<div class="clearfix"></div>':'',
            ]
        ])?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
    "size"=>Modal::SIZE_LARGE,
    "dialogOptions"=>["data-modal-size"=>Modal::SIZE_LARGE,]
])?>
<?php Modal::end(); ?>
