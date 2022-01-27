<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entidades_base.m_parroquias".
 *
 * @property int $id_parroquia N°
 * @property int $id_municipio Municipio
 * @property string $parroquia Perroquia
 * @property string $ip_log Ip Log
 * @property int $usuario_creador Usuario Creador
 * @property int|null $usuario_modificador Usuario Modificador
 * @property string $fecha_creacion Fecha de Creación
 * @property string|null $fecha_modificacion Fecha de Modificación
 * @property bool $estatus Estatus
 */
class Parroquias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entidades_base.m_parroquias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_municipio', 'parroquia', 'ip_log', 'usuario_creador', 'fecha_creacion'], 'required'],
            [['id_municipio', 'usuario_creador', 'usuario_modificador'], 'default', 'value' => null],
            [['id_municipio', 'usuario_creador', 'usuario_modificador'], 'integer'],
            [['ip_log'], 'string'],
            [['fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['estatus'], 'boolean'],
            [['parroquia'], 'string', 'max' => 100],
            [['id_municipio'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['id_municipio' => 'id_municipio']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_parroquia' => 'N°',
            'id_municipio' => 'Municipio',
            'parroquia' => 'Perroquia',
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
     * @return ParroquiasQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ParroquiasQuery(get_called_class());
    }
}
