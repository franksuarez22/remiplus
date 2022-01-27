<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entidades_base.m_municipios".
 *
 * @property int $id_municipio N°
 * @property int $id_estado Estado
 * @property string $municipio Estado
 * @property string $ip_log Ip Log
 * @property int|null $usuario_creador Usuario Creador
 * @property int|null $usuario_modificador Usuario Modificador
 * @property string|null $fecha_creacion Fecha de Creación
 * @property string|null $fecha_modificacion Fecha de Modificación
 * @property bool $estatus Estatus
 */
class Municipios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entidades_base.m_municipios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_estado', 'municipio', 'ip_log'], 'required'],
            [['id_estado', 'usuario_creador', 'usuario_modificador'], 'default', 'value' => null],
            [['id_estado', 'usuario_creador', 'usuario_modificador'], 'integer'],
            [['ip_log'], 'string'],
            [['fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['estatus'], 'boolean'],
            [['municipio'], 'string', 'max' => 50],
            [['id_estado'], 'exist', 'skipOnError' => true, 'targetClass' => Estados::className(), 'targetAttribute' => ['id_estado' => 'id_estado']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_municipio' => 'Id Municipio',
            'id_estado' => 'Id Estado',
            'municipio' => 'Municipio',
            'ip_log' => 'Ip Log',
            'usuario_creador' => 'Usuario Creador',
            'usuario_modificador' => 'Usuario Modificador',
            'fecha_creacion' => 'Fecha Creacion',
            'fecha_modificacion' => 'Fecha Modificacion',
            'estatus' => 'Estatus',
        ];
    }
}
