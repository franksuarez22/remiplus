<?php

namespace app\models;

use Yii;
use app\components\ObtenerLogSeguridad;

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
            [['id_categoria_incidencia', 'nombre_tipo_incidencia'], 'required'],
            [['id_categoria_incidencia', 'usuario_creador', 'usuario_modificador'], 'default', 'value' => null],
            [['id_categoria_incidencia', 'usuario_creador', 'usuario_modificador'], 'integer'],
            [['ip_log'], 'string'],
            [['fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['estatus'], 'boolean'],
            [['nombre_tipo_incidencia'], 'string', 'max' => 250],
            //[[''], 'filter', 'filter' => 'mb_strtoupper'],
            [['fecha_creacion'], 'default', 'value' => ObtenerLogSeguridad::cdbexpression()],
            [['fecha_modificacion'], 'filter', 'filter' => function(){return ObtenerLogSeguridad::cdbexpression();},'when' => function($model){return !$model->isNewRecord;}],
            [['usuario_creador'], 'default', 'value' => Yii::$app->user->id],
            [['usuario_modificador'], 'filter', 'filter' => function(){return Yii::$app->user->id;},'when' => function($model){return !$model->isNewRecord;}],
            [['ip_log'], 'filter', 'filter' => function(){return ObtenerLogSeguridad::getRealIpAddr();}],
            [['id_categoria_incidencia'], 'exist', 'skipOnError' => true, 'targetClass' => Categoriaincidencia::className(), 'targetAttribute' => ['id_categoria_incidencia' => 'id_categoria_incidencia']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_incidencia' => Yii::t('app', 'Tipo Incidencia'),
            'id_categoria_incidencia' => Yii::t('app', 'Categoria Incidencia'),
            'nombre_tipo_incidencia' => Yii::t('app', 'Nombre de Tipo Incidencia'),
            'ip_log' => Yii::t('app', 'Ip Log'),
            'usuario_creador' => Yii::t('app', 'Usuario Creador'),
            'usuario_modificador' => Yii::t('app', 'Usuario Modificador'),
            'fecha_creacion' => Yii::t('app', 'Fecha Creacion'),
            'fecha_modificacion' => Yii::t('app', 'Fecha Modificacion'),
            'estatus' => Yii::t('app', 'Estatus'),
        ];
    }
}
