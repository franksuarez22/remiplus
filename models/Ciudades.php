<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entidades_base.m_ciudades".
 *
 * @property int $id_ciudad N°
 * @property int $id_parroquia Parroquia
 * @property string $ciudad Ciudad
 * @property string $ip_log Ip Log
 * @property int $usuario_creador Usuario Creador
 * @property int|null $usuario_modificador Usuario Modificador
 * @property string $fecha_creacion Fecha de Creación
 * @property string|null $fecha_modificacion Fecha de Modificación
 * @property bool $estatus Estatus
 */
class Ciudades extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entidades_base.m_ciudades';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_parroquia', 'ciudad', 'ip_log', 'usuario_creador', 'fecha_creacion'], 'required'],
            [['id_parroquia', 'usuario_creador', 'usuario_modificador'], 'default', 'value' => null],
            [['id_parroquia', 'usuario_creador', 'usuario_modificador'], 'integer'],
            [['ip_log'], 'string'],
            [['fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['estatus'], 'boolean'],
            [['ciudad'], 'string', 'max' => 500],
            [['id_parroquia'], 'exist', 'skipOnError' => true, 'targetClass' => EntidadesBaseMParroquias::className(), 'targetAttribute' => ['id_parroquia' => 'id_parroquia']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_ciudad' => 'Id Ciudad',
            'id_parroquia' => 'Id Parroquia',
            'ciudad' => 'Ciudad',
            'ip_log' => 'Ip Log',
            'usuario_creador' => 'Usuario Creador',
            'usuario_modificador' => 'Usuario Modificador',
            'fecha_creacion' => 'Fecha Creacion',
            'fecha_modificacion' => 'Fecha Modificacion',
            'estatus' => 'Estatus',
        ];
    }
}
