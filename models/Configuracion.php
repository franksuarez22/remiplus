<?php

namespace app\models;

use Yii;
use app\components\ObtenerLogSeguridad;

/**
 * This is the model class for table "entidades_base.m_configuracion".
 *
 * @property int $id_configuracion N°
 * @property int $vencimiento_token_sesion Tiempo de vigencia del token de sesión en minutos
 * @property int $vencimiento_sesion_interno Tiempo de vigencia de sesión de usuarios internos en minutos
 * @property int $vencimiento_sesion_externo Tiempo de vigencia de sesión de usuarios externos en minutos
 * @property int $cantidad_incidencias_denuncia Cantidad de incidencias mínimas para conciderar una denuncia
 * @property int $distancia_incidencias Distancia en metros para agrupar incidencias
 * @property int $peso_imagen Peso máximo de la imagen en MB
 * @property string $ip_log Ip Log
 * @property int $usuario_creador Usuario Creador
 * @property int|null $usuario_modificador Usuario Modificador
 * @property string $fecha_creacion Fecha de Creación
 * @property string|null $fecha_modificacion Fecha de Modificación
 * @property bool $estatus Estatus
 */
class Configuracion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entidades_base.m_configuracion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vencimiento_token_sesion', 'vencimiento_sesion_interno', 'vencimiento_sesion_externo', 'cantidad_incidencias_denuncia', 'distancia_incidencias', 'peso_imagen'], 'required'],
            [['vencimiento_token_sesion', 'vencimiento_sesion_interno', 'vencimiento_sesion_externo', 'cantidad_incidencias_denuncia', 'distancia_incidencias', 'peso_imagen', 'usuario_creador', 'usuario_modificador'], 'default', 'value' => null],
            [['vencimiento_token_sesion', 'vencimiento_sesion_interno', 'vencimiento_sesion_externo', 'cantidad_incidencias_denuncia', 'distancia_incidencias', 'peso_imagen', 'usuario_creador', 'usuario_modificador'], 'integer'],
            [['ip_log'], 'string'],
            [['fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['estatus'], 'boolean'],
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
            'id_configuracion' => Yii::t('app', 'Id Configuracion'),
            'vencimiento_token_sesion' => Yii::t('app', 'Vencimiento Token Sesion'),
            'vencimiento_sesion_interno' => Yii::t('app', 'Vencimiento Sesion Interno'),
            'vencimiento_sesion_externo' => Yii::t('app', 'Vencimiento Sesion Externo'),
            'cantidad_incidencias_denuncia' => Yii::t('app', 'Cantidad Incidencias Denuncia'),
            'distancia_incidencias' => Yii::t('app', 'Distancia Incidencias'),
            'peso_imagen' => Yii::t('app', 'Peso Imagen'),
            'ip_log' => Yii::t('app', 'Ip Log'),
            'usuario_creador' => Yii::t('app', 'Usuario Creador'),
            'usuario_modificador' => Yii::t('app', 'Usuario Modificador'),
            'fecha_creacion' => Yii::t('app', 'Fecha Creacion'),
            'fecha_modificacion' => Yii::t('app', 'Fecha Modificacion'),
            'estatus' => Yii::t('app', 'Estatus'),
        ];
    }
}
