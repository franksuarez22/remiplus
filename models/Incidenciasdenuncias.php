<?php

namespace app\models;

use Yii;
use app\components\ObtenerLogSeguridad;

/**
 * This is the model class for table "incidencias.r_incidencias_denuncias".
 *
 * @property int $id_incidencia_denuncia N°
 * @property int $id_incidencia Incidencia
 * @property int $id_denuncia Denuncia
 * @property string $ip_log Ip Log
 * @property int $usuario_creador Usuario Creador
 * @property int|null $usuario_modificador Usuario Modificador
 * @property string $fecha_creacion Fecha de Creación
 * @property string|null $fecha_modificacion Fecha de Modificación
 * @property bool $estatus Estatus
 */
class Incidenciasdenuncias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'incidencias.r_incidencias_denuncias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_incidencia', 'id_denuncia'], 'required'],
            [['id_incidencia', 'id_denuncia', 'usuario_creador', 'usuario_modificador'], 'default', 'value' => null],
            [['id_incidencia', 'id_denuncia', 'usuario_creador', 'usuario_modificador'], 'integer'],
            [['ip_log'], 'string'],
            [['fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['estatus'], 'boolean'],
            //[[''], 'filter', 'filter' => 'mb_strtoupper'],
            [['fecha_creacion'], 'default', 'value' => ObtenerLogSeguridad::cdbexpression()],
            [['fecha_modificacion'], 'filter', 'filter' => function(){return ObtenerLogSeguridad::cdbexpression();},'when' => function($model){return !$model->isNewRecord;}],
            [['usuario_creador'], 'default', 'value' => Yii::$app->user->id],
            [['usuario_modificador'], 'filter', 'filter' => function(){return Yii::$app->user->id;},'when' => function($model){return !$model->isNewRecord;}],
            [['ip_log'], 'filter', 'filter' => function(){return ObtenerLogSeguridad::getRealIpAddr();}],
            [['id_incidencia'], 'exist', 'skipOnError' => true, 'targetClass' => Incidencias::className(), 'targetAttribute' => ['id_incidencia' => 'id_incidencia']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_incidencia_denuncia' => Yii::t('app', 'Id Incidencia Denuncia'),
            'id_incidencia' => Yii::t('app', 'Id Incidencia'),
            'id_denuncia' => Yii::t('app', 'Id Denuncia'),
            'ip_log' => Yii::t('app', 'Ip Log'),
            'usuario_creador' => Yii::t('app', 'Usuario Creador'),
            'usuario_modificador' => Yii::t('app', 'Usuario Modificador'),
            'fecha_creacion' => Yii::t('app', 'Fecha Creacion'),
            'fecha_modificacion' => Yii::t('app', 'Fecha Modificacion'),
            'estatus' => Yii::t('app', 'Estatus'),
        ];
    }
}
