<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entidades_base.m_nacionalidad".
 *
 * @property int $id_nacionalidad N°
 * @property string $descripcion_nacionalidad Nacionalidad
 * @property string $ip_log Ip Log
 * @property int $usuario_creador Usuario Creador
 * @property int|null $usuario_modificador Usuario Modificador
 * @property string $fecha_creacion Fecha de Creación
 * @property string|null $fecha_modificacion Fecha de Modificación
 * @property bool $estatus Estatus
 */
class Nacionalidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entidades_base.m_nacionalidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion_nacionalidad', 'ip_log', 'usuario_creador', 'fecha_creacion'], 'required'],
            [['ip_log'], 'string'],
            [['usuario_creador', 'usuario_modificador'], 'default', 'value' => null],
            [['usuario_creador', 'usuario_modificador'], 'integer'],
            [['fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['estatus'], 'boolean'],
            [['descripcion_nacionalidad'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_nacionalidad' => 'N°',
            'descripcion_nacionalidad' => 'Nacionalidad',
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
     * @return NacionalidadQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NacionalidadQuery(get_called_class());
    }
}
