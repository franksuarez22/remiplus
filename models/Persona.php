<?php

namespace app\models;

use Yii;
use app\components\ObtenerLogSeguridad;

/**
 * This is the model class for table "persona".
 *
 * @property string|null $letra
 * @property int|null $cedula
 * @property string|null $pais_origen
 * @property string|null $nacionalidad
 * @property string|null $primer_nombre
 * @property string|null $segundo_nombre
 * @property string|null $primer_apellido
 * @property string|null $segundo_apellido
 * @property string|null $fecha_nacimiento
 * @property string|null $codigo_objecion
 * @property string|null $codigo_oficina
 * @property string|null $codigo_estado_civil
 * @property string|null $naturalizado
 * @property string|null $sexo
 * @property int $id_persona
 */
class Persona extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'persona';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('saime');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cedula'], 'default', 'value' => null],
            [['cedula'], 'integer'],
            [['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido'], 'string'],
            [['fecha_nacimiento'], 'safe'],
            [['letra', 'sexo'], 'string', 'max' => 1],
            [['pais_origen', 'nacionalidad', 'codigo_objecion', 'codigo_oficina', 'codigo_estado_civil', 'naturalizado'], 'string', 'max' => 3],
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
            'letra' => Yii::t('app', 'Letra'),
            'cedula' => Yii::t('app', 'Cédula'),
            'pais_origen' => Yii::t('app', 'País Origen'),
            'nacionalidad' => Yii::t('app', 'Nacionalidad'),
            'primer_nombre' => Yii::t('app', 'Primer Nombre'),
            'segundo_nombre' => Yii::t('app', 'Segundo Nombre'),
            'primer_apellido' => Yii::t('app', 'Primer Apellido'),
            'segundo_apellido' => Yii::t('app', 'Segundo Apellido'),
            'fecha_nacimiento' => Yii::t('app', 'Fecha Nacimiento'),
            'codigo_objecion' => Yii::t('app', 'Codigo Objecion'),
            'codigo_oficina' => Yii::t('app', 'Codigo Oficina'),
            'codigo_estado_civil' => Yii::t('app', 'Codigo Estado Civil'),
            'naturalizado' => Yii::t('app', 'Naturalizado'),
            'sexo' => Yii::t('app', 'Sexo'),
            'id_persona' => Yii::t('app', 'Id Persona'),
        ];
    }
}
