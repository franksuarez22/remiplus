<?php

namespace app\models;

use Yii;
use app\components\ObtenerLogSeguridad;

/**
 * This is the model class for table "incidencias.r_denuncias".
 *
 * @property int $id_denuncia N°
 * @property int $id_tipo_incidencia Tipo de Incidencia
 * @property int $id_estado Estado
 * @property int $id_municipio Municipio
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
            [['id_tipo_incidencia', 'id_estado', 'id_municipio', 'id_parroquia', 'id_ciudad', 'descripcion', 'direccion', 'latitud', 'longitud'], 'required'],
            [['id_tipo_incidencia', 'id_estado', 'id_municipio', 'id_parroquia', 'id_ciudad', 'usuario_creador', 'usuario_modificador'], 'default', 'value' => null],
            [['id_tipo_incidencia', 'id_estado', 'id_municipio', 'id_parroquia', 'id_ciudad', 'usuario_creador', 'usuario_modificador'], 'integer'],
            [['descripcion', 'direccion', 'ip_log'], 'string'],
            [['latitud', 'longitud'], 'number'],
            [['fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['estatus'], 'boolean'],
            [['punto_referencia'], 'string', 'max' => 250],
            //[[''], 'filter', 'filter' => 'mb_strtoupper'],
            [['fecha_creacion'], 'default', 'value' => ObtenerLogSeguridad::cdbexpression()],
            [['fecha_modificacion'], 'filter', 'filter' => function(){return ObtenerLogSeguridad::cdbexpression();},'when' => function($model){return !$model->isNewRecord;}],
            [['usuario_creador'], 'default', 'value' => Yii::$app->user->id],
            [['usuario_modificador'], 'filter', 'filter' => function(){return Yii::$app->user->id;},'when' => function($model){return !$model->isNewRecord;}],
            [['ip_log'], 'filter', 'filter' => function(){return ObtenerLogSeguridad::getRealIpAddr();}],
            [['id_ciudad'], 'exist', 'skipOnError' => true, 'targetClass' => Ciudades::className(), 'targetAttribute' => ['id_ciudad' => 'id_ciudad']],
            [['id_estado'], 'exist', 'skipOnError' => true, 'targetClass' => Estados::className(), 'targetAttribute' => ['id_estado' => 'id_estado']],
            [['id_municipio'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['id_municipio' => 'id_municipio']],
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
            'id_denuncia' => Yii::t('app', 'Denuncia'),
            'id_tipo_incidencia' => Yii::t('app', 'Tipo Incidencia'),
            'id_estado' => Yii::t('app', 'Estado'),
            'id_municipio' => Yii::t('app', 'Municipio'),
            'id_parroquia' => Yii::t('app', 'Parroquia'),
            'id_ciudad' => Yii::t('app', 'Ciudad'),
            'descripcion' => Yii::t('app', 'Descripción'),
            'direccion' => Yii::t('app', 'Dirección'),
            'punto_referencia' => Yii::t('app', 'Punto Referencia'),
            'latitud' => Yii::t('app', 'Latitud'),
            'longitud' => Yii::t('app', 'Longitud'),
            'ip_log' => Yii::t('app', 'Ip Log'),
            'usuario_creador' => Yii::t('app', 'Usuario Creador'),
            'usuario_modificador' => Yii::t('app', 'Usuario Modificador'),
            'fecha_creacion' => Yii::t('app', 'Fecha Creacion'),
            'fecha_modificacion' => Yii::t('app', 'Fecha Modificacion'),
            'estatus' => Yii::t('app', 'Estatus'),
        ];
    }
}
