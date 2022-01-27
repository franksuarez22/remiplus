<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "incidencias.r_denuncias".
 *
 * @property int $id_denuncia N°
 * @property int $id_tipo_incidencia Tipo de Incidencia
 * @property int $id_parroquia Parroquia
 * @property int $id_ciudad Ciudad
 * @property string $descripcion Descripción de la denuncia
 * @property string $direccion Dirección de la denuncia
 * @property string|null $punto_referencia Punto de referencia
 * @property float $latitud Latitud
 * @property float $longitud Longitud
 * @property string $ip_log Ip Log
 * @property int $usuario_creador Usuario Creador
 * @property int|null $usuario_modificador Usuario Modificador
 * @property string $fecha_creacion Fecha de Creación
 * @property string|null $fecha_modificacion Fecha de Modificación
 * @property bool $estatus Estatus
 */
class Denuncias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'incidencias.r_denuncias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tipo_incidencia', 'id_parroquia', 'id_ciudad', 'descripcion', 'direccion', 'latitud', 'longitud', 'ip_log', 'usuario_creador', 'fecha_creacion'], 'required'],
            [['id_tipo_incidencia', 'id_parroquia', 'id_ciudad', 'usuario_creador', 'usuario_modificador'], 'default', 'value' => null],
            [['id_tipo_incidencia', 'id_parroquia', 'id_ciudad', 'usuario_creador', 'usuario_modificador'], 'integer'],
            [['descripcion', 'direccion', 'ip_log'], 'string'],
            [['latitud', 'longitud'], 'number'],
            [['fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['estatus'], 'boolean'],
            [['punto_referencia'], 'string', 'max' => 250],
            [['id_ciudad'], 'exist', 'skipOnError' => true, 'targetClass' => Ciudades::className(), 'targetAttribute' => ['id_ciudad' => 'id_ciudad']],
            [['id_parroquia'], 'exist', 'skipOnError' => true, 'targetClass' => Parroquias::className(), 'targetAttribute' => ['id_parroquia' => 'id_parroquia']],
            [['id_tipo_incidencia'], 'exist', 'skipOnError' => true, 'targetClass' => Tipoincidencia::className(), 'targetAttribute' => ['id_tipo_incidencia' => 'id_tipo_incidencia']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_denuncia' => 'N°',
            'id_tipo_incidencia' => 'Tipo de Incidencia',
            'id_parroquia' => 'Parroquia',
            'id_ciudad' => 'Ciudad',
            'descripcion' => 'Descripción de la denuncia',
            'direccion' => 'Dirección de la denuncia',
            'punto_referencia' => 'Punto de referencia',
            'latitud' => 'Latitud',
            'longitud' => 'Longitud',
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
     * @return DenunciasQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DenunciasQuery(get_called_class());
    }
}
