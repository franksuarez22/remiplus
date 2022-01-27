<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entidades_base.m_tipo_incidencia".
 *
 * @property int $id_tipo_incidencia N°
 * @property int $id_categoria_incidencia Categoría
 * @property string $nombre_tipo_incidencia Tipo de incidencia
 * @property string $ip_log Ip Log
 * @property int $usuario_creador Usuario Creador
 * @property int|null $usuario_modificador Usuario Modificador
 * @property string $fecha_creacion Fecha de Creación
 * @property string|null $fecha_modificacion Fecha de Modificación
 * @property bool $estatus Estatus
 */
class Tipoincidencia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entidades_base.m_tipo_incidencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_categoria_incidencia', 'nombre_tipo_incidencia', 'ip_log', 'usuario_creador', 'fecha_creacion'], 'required'],
            [['id_categoria_incidencia', 'usuario_creador', 'usuario_modificador'], 'default', 'value' => null],
            [['id_categoria_incidencia', 'usuario_creador', 'usuario_modificador'], 'integer'],
            [['ip_log'], 'string'],
            [['fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['estatus'], 'boolean'],
            [['nombre_tipo_incidencia'], 'string', 'max' => 250],
            [['id_categoria_incidencia'], 'exist', 'skipOnError' => true, 'targetClass' => MCategoriaIncidencia::className(), 'targetAttribute' => ['id_categoria_incidencia' => 'id_categoria_incidencia']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_incidencia' => 'N°',
            'id_categoria_incidencia' => 'Categoría',
            'nombre_tipo_incidencia' => 'Tipo de incidencia',
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
     * @return TipoIncidenciaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TipoIncidenciaQuery(get_called_class());
    }
}
