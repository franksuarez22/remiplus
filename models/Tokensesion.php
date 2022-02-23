<?php

namespace app\models;

use Yii;
use app\components\ObtenerLogSeguridad;

/**
 * This is the model class for table "incidencias.r_token_sesion".
 *
 * @property int $id_token_sesion N°
 * @property string $token_sesion Token
 * @property int $cedula Cédula
 * @property string $telefono_contacto Teléfono de contacto
 * @property string $correo_electronico Correo electrónico
 * @property int $fecha_inicio Fecha de inicio
 * @property int $fecha_expiracion Fecha de expiración
 * @property string $ip_log Ip Log
 * @property int $usuario_creador Usuario Creador
 * @property int|null $usuario_modificador Usuario Modificador
 * @property string $fecha_creacion Fecha de Creación
 * @property string|null $fecha_modificacion Fecha de Modificación
 * @property bool $estatus Estatus
 */
class Tokensesion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'incidencias.r_token_sesion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['token_sesion', 'cedula', 'telefono_contacto', 'correo_electronico', 'fecha_inicio', 'fecha_expiracion'], 'required'],
            [['cedula', 'fecha_inicio', 'fecha_expiracion', 'usuario_creador', 'usuario_modificador'], 'default', 'value' => null],
            [['cedula', 'fecha_inicio', 'fecha_expiracion', 'usuario_creador', 'usuario_modificador'], 'integer'],
            [['ip_log'], 'string'],
            [['fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['estatus'], 'boolean'],
            [['token_sesion'], 'string', 'max' => 10],
            [['telefono_contacto'], 'string', 'max' => 11],
            [['correo_electronico'], 'string', 'max' => 150],
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
            'id_token_sesion' => Yii::t('app', 'Token Sesion'),
            'token_sesion' => Yii::t('app', 'Token Sesion'),
            'cedula' => Yii::t('app', 'Cedula'),
            'telefono_contacto' => Yii::t('app', 'Telefono Contacto'),
            'correo_electronico' => Yii::t('app', 'Correo Electronico'),
            'fecha_inicio' => Yii::t('app', 'Fecha Inicio'),
            'fecha_expiracion' => Yii::t('app', 'Fecha Expiracion'),
            'ip_log' => Yii::t('app', 'Ip Log'),
            'usuario_creador' => Yii::t('app', 'Usuario Creador'),
            'usuario_modificador' => Yii::t('app', 'Usuario Modificador'),
            'fecha_creacion' => Yii::t('app', 'Fecha Creacion'),
            'fecha_modificacion' => Yii::t('app', 'Fecha Modificacion'),
            'estatus' => Yii::t('app', 'Estatus'),
        ];
    }
}
