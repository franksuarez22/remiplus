<?php

namespace app\models;

use Yii;
use app\components\ObtenerLogSeguridad;

/**
 * This is the model class for table "entidades_base.m_estados".
 *
 * @property int $id_estado N°
 * @property string $estado Estado
 * @property string $ip_log Ip Log
 * @property int $usuario_creador Usuario Creador
 * @property int|null $usuario_modificador Usuario Modificador
 * @property string $fecha_creacion Fecha de Creación
 * @property string|null $fecha_modificacion Fecha de Modificación
 * @property bool $estatus Estatus
 */
class Estados extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entidades_base.m_estados';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estado'], 'required'],
            [['ip_log'], 'string'],
            [['usuario_creador', 'usuario_modificador'], 'default', 'value' => null],
            [['usuario_creador', 'usuario_modificador'], 'integer'],
            [['fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['estatus'], 'boolean'],
            [['estado'], 'string', 'max' => 50],
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
            'id_estado' => 'Id Estado',
            'estado' => 'Estado',
            'ip_log' => 'Ip Log',
            'usuario_creador' => 'Usuario Creador',
            'usuario_modificador' => 'Usuario Modificador',
            'fecha_creacion' => 'Fecha Creacion',
            'fecha_modificacion' => 'Fecha Modificacion',
            'estatus' => 'Estatus',
        ];
    }
}
