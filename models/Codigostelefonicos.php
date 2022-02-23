<?php

namespace app\models;

use Yii;
use app\components\ObtenerLogSeguridad;

/**
 * This is the model class for table "entidades_base.m_codigos_telefonicos".
 *
 * @property int $id_codigos_telefonicos
 * @property string $codigo_telefonico
 * @property string $tippo_telefonia
 * @property string $compania_telefonica
 * @property string $ip_log
 * @property int $usuario_creador
 * @property int|null $usuario_modificador
 * @property string $fecha_creacion
 * @property string|null $fecha_modificacion
 * @property bool|null $estatus
 */
class Codigostelefonicos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entidades_base.m_codigos_telefonicos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_telefonico', 'tippo_telefonia', 'compania_telefonica'], 'required'],
            [['usuario_creador', 'usuario_modificador'], 'default', 'value' => null],
            [['usuario_creador', 'usuario_modificador'], 'integer'],
            [['fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['estatus'], 'boolean'],
            [['codigo_telefonico'], 'string', 'max' => 4],
            [['tippo_telefonia'], 'string', 'max' => 10],
            [['compania_telefonica'], 'string', 'max' => 150],
            [['ip_log'], 'string', 'max' => 250],
            //[[''], 'filter', 'filter' => 'mb_strtoupper'],
            [['fecha_creacion'], 'default', 'value' => ObtenerLogSeguridad::cdbexpression()],
            [['fecha_modificacion'], 'filter', 'filter' => function(){return ObtenerLogSeguridad::cdbexpression();},'when' => function($model){return !$model->isNewRecord;}],
            [['usuario_creador'], 'default', 'value' => Yii::$app->user->id],
            [['usuario_modificador'], 'filter', 'filter' => function(){return Yii::$app->user->id;},'when' => function($model){return !$model->isNewRecord;}],
            [['ip_log'], 'filter', 'filter' => function(){return ObtenerLogSeguridad::getRealIpAddr();}],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_codigos_telefonicos' => Yii::t('app', 'Codigos Telefónicos'),
            'codigo_telefonico' => Yii::t('app', 'Codigo Telefónico'),
            'tippo_telefonia' => Yii::t('app', 'Tipo de Telefonía'),
            'compania_telefonica' => Yii::t('app', 'Compañia Telefónica'),
            'ip_log' => Yii::t('app', 'Ip Log'),
            'usuario_creador' => Yii::t('app', 'Usuario Creador'),
            'usuario_modificador' => Yii::t('app', 'Usuario Modificador'),
            'fecha_creacion' => Yii::t('app', 'Fecha Creacion'),
            'fecha_modificacion' => Yii::t('app', 'Fecha Modificacion'),
            'estatus' => Yii::t('app', 'Estatus'),
        ];
    }
}
