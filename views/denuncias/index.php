<?php
use yii\helpers\Url;
use yii\bootstrap4\Html;
use yii\bootstrap4\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use mdm\admin\components\Helper;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DenunciasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Denuncias');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$this->registerJs("
    modal = new ModalRemote('#ajaxCrudModal');
    $('#nueva_denuncia').on('click', function (event) {
        event.preventDefault();
        // Collect all selected ID's
        var selectedIds = [];
        $('input:checkbox[name=\"selection[]\"]').each(function () {
            if (this.checked)
                selectedIds.push($(this).val());
        });
        if (selectedIds.length == 0) {
            // If no selected ID's show warning
            modal.show();
            modal.setTitle('No hay selección');
            modal.setContent(\"Debe seleccionar 10 incidencias para formalizar la denuncia. Usted tiene \"+selectedIds.length+\" incidencias seleccionadas\");
            modal.addFooterButton(\"Cerrar\",'button', 'btn btn-default', function (button, event) {
                this.hide();
            });
        } else {
            // Open modal
            modal.open(this, selectedIds);
        }
    });
", View::POS_READY, 'boton');

    echo"<br><br>";

$nuevo=$exportar='';
    if(Helper::checkRoute('create')){
      /*echo $nuevo = Html::a('Nueva Denuncia <i class="glyphicon glyphicon-plus"></i>', ['/denuncias/create'],
                ['role'=>'modal-remote-bulkk','title'=> 'Nueva Denuncia','class'=>'btn btn-primary','id'=>'nueva_denuncia','data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                    'data-request-method'=>'post',
                                    'data-confirm-title'=>'¿Está seguro?',
                                    'data-confirm-message'=>'Se asociaran las incidencias seleccionadas en una sola denuncia formal']);*/
    }
    echo"<br><br>";
    if(Helper::checkRoute('gridview/export/download')){
       $exportar = '{export}';
    }
?>
<div class="denuncias-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable-denuncias',
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
                'heading' => '<i class="glyphicon glyphicon-list"></i> Listado de Denuncias',
                'before'=>'<em>* Cambie el tamaño de las columnas de la tabla como una hoja de cálculo arrastrando los bordes de la columna.</em>',
                /*'after'=>Helper::checkRoute('bulk-delete') ? BulkButtonWidget::widget([
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
                        '<div class="clearfix"></div>':'',*/
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
