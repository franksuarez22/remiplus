<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entidades_base.m_estatus_incidencia".
 *
 * @property int $id_estatus_incidencia N°
 * @property string $estatus_incidencia Estatus de la incidencia
 * @property string $ip_log Ip Log
 * @property int $usuario_creador Usuario Creador
 * @property int|null $usuario_modificador Usuario Modificador
 * @property string $fecha_creacion Fecha de Creación
 * @property string|null $fecha_modificacion Fecha de Modificación
 * @property bool $estatus Estatus
 */
class Estatusincidencia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entidades_base.m_estatus_incidencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estatus_incidencia', 'ip_log', 'usuario_creador', 'fecha_creacion'], 'required'],
            [['ip_log'], 'string'],
            [['usuario_creador', 'usuario_modificador'], 'default', 'value' => null],
            [['usuario_creador', 'usuario_modificador'], 'integer'],
            [['fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['estatus'], 'boolean'],
            [['estatus_incidencia'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_estatus_incidencia' => 'N°',
            'estatus_incidencia' => 'Estatus de la incidencia',
            'ip_log' => 'Ip Log',
            'usuario_creador' => 'Usuario Creador',
            'usuario_modificador' => 'Usuario Modificador',
            'fecha_creacion' => 'Fecha de Creación',
            'fecha_modificacion' => 'Fecha de Modificación',
            'estatus' => 'Estatus',
        ];
    }

    /**
     * {@inheritdoc}
     * @return EstatusincidenciaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EstatusincidenciaQuery(get_called_class());
    }
}
