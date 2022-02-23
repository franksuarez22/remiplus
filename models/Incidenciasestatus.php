<?php

namespace app\models;

use Yii;
use app\components\ObtenerLogSeguridad;

/**
 * This is the model class for table "incidencias.r_incidencias_estatus".
 *
 * @property int $id_incidencia_estatus N°
 * @property int $id_denuncia Incidencia
 * @property int $id_estatus_incidencia Estatus
 * @property string $fecha_cambio_estatus Fecha
 * @property string|null $observaciones Observaciones
 * @property string $ip_log Ip Log
 * @property int $usuario_creador Usuario Creador
 * @property int|null $usuario_modificador Usuario Modificador
 * @property string $fecha_creacion Fecha de Creación
 * @property string|null $fecha_modificacion Fecha de Modificación
 * @property bool $estatus Estatus
 */
class Incidenciasestatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'incidencias.r_incidencias_estatus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_denuncia', 'id_estatus_incidencia', 'fecha_cambio_estatus'], 'required'],
            [['id_denuncia', 'id_estatus_incidencia', 'usuario_creador', 'usuario_modificador'], 'default', 'value' => null],
            [['id_denuncia', 'id_estatus_incidencia', 'usuario_creador', 'usuario_modificador'], 'integer'],
            [['fecha_cambio_estatus', 'fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['observaciones', 'ip_log'], 'string'],
            [['estatus'], 'boolean'],
            //[[''], 'filter', 'filter' => 'mb_strtoupper'],
            [['fecha_creacion'], 'default', 'value' => ObtenerLogSeguridad::cdbexpression()],
            [['fecha_modificacion'], 'filter', 'filter' => function(){return ObtenerLogSeguridad::cdbexpression();},'when' => function($model){return !$model->isNewRecord;}],
            [['usuario_creador'], 'default', 'value' => Yii::$app->user->id],
            [['usuario_modificador'], 'filter', 'filter' => function(){return Yii::$app->user->id;},'when' => function($model){return !$model->isNewRecord;}],
            [['ip_log'], 'filter', 'filter' => function(){return ObtenerLogSeguridad::getRealIpAddr();}],
            [['id_estatus_incidencia'], 'exist', 'skipOnError' => true, 'targetClass' => Estatusincidencia::className(), 'targetAttribute' => ['id_estatus_incidencia' => 'id_estatus_incidencia']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_incidencia_estatus' => Yii::t('app', 'Id Incidencia Estatus'),
            'id_denuncia' => Yii::t('app', 'Id Denuncia'),
            'id_estatus_incidencia' => Yii::t('app', 'Id Estatus Incidencia'),
            'fecha_cambio_estatus' => Yii::t('app', 'Fecha Cambio Estatus'),
            'observaciones' => Yii::t('app', 'Observaciones'),
            'ip_log' => Yii::t('app', 'Ip Log'),
            'usuario_creador' => Yii::t('app', 'Usuario Creador'),
            'usuario_modificador' => Yii::t('app', 'Usuario Modificador'),
            'fecha_creacion' => Yii::t('app', 'Fecha Creacion'),
            'fecha_modificacion' => Yii::t('app', 'Fecha Modificacion'),
            'estatus' => Yii::t('app', 'Estatus'),
        ];
    }
}
