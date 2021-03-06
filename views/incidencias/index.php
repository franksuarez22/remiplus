<?php
use yii\helpers\Url;
use yii\bootstrap4\Html;
use yii\bootstrap4\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use mdm\admin\components\Helper;
/* @var $this yii\web\View */
/* @var $searchModel app\models\IncidenciasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Registro de Incidencias');
//$this->title = Yii::t('app', 'Incidencias');
//$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

    echo"<br><br>";
$nuevo=$exportar='';
    if(Helper::checkRoute('/incidencias/create')){
        echo $nuevo = Html::a('Generar nueva Incidencia <i class="glyphicon glyphicon-plus"></i>', ['/incidencias/create'],
                ['data-pjax' => 1, 'role'=>'modal-remote', 'title'=> 'Generar nueva Incidencia','class'=>'btn btn-primary']);
    }
    if(Helper::checkRoute('/denuncias/create')){
        echo $nuevo = Html::a('Nueva Denuncia <i class="glyphicon glyphicon-plus"></i>', ['/denuncias/create'],
                ['role'=>'modal-remote-bulkk','title'=> 'Nueva Denuncia','class'=>'btn btn-primary','id'=>'nueva_denuncia','data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                    'data-request-method'=>'post',
                                    'data-confirm-title'=>'¿Está seguro?',
                                    'data-confirm-message'=>'Se asociaran las incidencias seleccionadas en una sola denuncia formal']);
    }    
    echo"<br><br>";
    if(Helper::checkRoute('gridview/export/download')){
       $exportar = '{export}';
    }
?>
<div class="incidencias-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable-incidencias',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    //$nuevo.
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
                'heading' => '<i class="glyphicon glyphicon-list"></i> Listado de Incidencias',
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
<?php 
if(!isset($tab)){
    Modal::begin([
        "id"=>"ajaxCrudModal",
        "footer"=>"",// always need it for jquery plugin
        "size"=>Modal::SIZE_LARGE,
        "dialogOptions"=>["data-modal-size"=>Modal::SIZE_LARGE,]
    ]);
    Modal::end(); 
}
?>
