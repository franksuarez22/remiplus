<?php
use yii\helpers\Url;
use yii\bootstrap4\Html;
use yii\bootstrap4\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\bootstrap4\Tabs;
use app\components\ComplementFunctions as cf;
//use kartik\tabs\TabsX;
/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Bandeja de incidencias');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);


echo $this->render('/personas/viewtabla' ,[
    'PersonasLabel' => new app\models\Personas,
    'Personas' => $Personas,
]); 
?>
<br>
<h4></h4> 
<br>
<?= Tabs::widget([
        'items' => [
            [
                'label' => 'Incidencias',
                'content' => $this->render('/incidencias/index' , [
                            'searchModel' => $searchModelIncidencias,
                            'dataProvider' => $dataProviderIncidencias,
                            'Personas' => $Personas,
                            'tab' => true
                            ]),
            ],
        ],
    ]);
?>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
    "size"=>Modal::SIZE_LARGE,
    "dialogOptions"=>["data-modal-size"=>Modal::SIZE_LARGE,]
])?>
<?php Modal::end(); ?>
