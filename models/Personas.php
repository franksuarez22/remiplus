<?php

namespace app\models;

use Yii;
use app\components\ObtenerLogSeguridad;

/**
 * This is the model class for table "incidencias.r_personas".
 *
 * @property int $id_persona N°
 * @property int $id_genero Género
 * @property int $id_nacionalidad Nacionalidad
 * @property int $id_estado Estado de recidencia
 * @property int $id_municipio Municipio de recidencia
 * @property int $id_parroquia Parroquia de recidencia
 * @property int $id_ciudad Ciudad de recidencia
 * @property int $cedula Cédula
 * @property string $primer_nombre Primer nombre
 * @property string|null $segundo_nombre Segundo nombre
 * @property string $primer_apellido Primer apellido
 * @property string|null $segundo_apellido Segundo apellido
 * @property string|null $fecha_nacimiento Fecha de nacimiento
 * @property string $telefono_contacto Teléfono de contacto
 * @property string $correo_electronico Correo electrónico
 * @property string $ip_log Ip Log
 * @property int $usuario_creador Usuario Creador
 * @property int|null $usuario_modificador Usuario Modificador
 * @property string $fecha_creacion Fecha de Creación
 * @property string|null $fecha_modificacion Fecha de Modificación
 * @property bool $estatus Estatus
 */
class Personas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'incidencias.r_personas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_genero', 'id_nacionalidad', 'id_estado', 'id_municipio', 'id_parroquia', 'id_ciudad', 'cedula', 'primer_nombre', 'primer_apellido', 'telefono_contacto', 'correo_electronico'], 'required'],
            [['id_genero', 'id_nacionalidad', 'id_estado', 'id_municipio', 'id_parroquia', 'id_ciudad', 'cedula', 'usuario_creador', 'usuario_modificador'], 'default', 'value' => null],
            [['id_genero', 'id_nacionalidad', 'id_estado', 'id_municipio', 'id_parroquia', 'id_ciudad', 'cedula', 'usuario_creador', 'usuario_modificador'], 'integer'],
            [['fecha_nacimiento', 'fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['ip_log'], 'string'],
            [['estatus'], 'boolean'],
            [['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'correo_electronico'], 'string', 'max' => 150],
            [['telefono_contacto'], 'string', 'max' => 11],
            //[[''], 'filter', 'filter' => 'mb_strtoupper'],
            [['fecha_creacion'], 'default', 'value' => ObtenerLogSeguridad::cdbexpression()],
            [['fecha_modificacion'], 'filter', 'filter' => function(){return ObtenerLogSeguridad::cdbexpression();},'when' => function($model){return !$model->isNewRecord;}],
            [['usuario_creador'], 'default', 'value' => isset(Yii::$app->user->id)? Yii::$app->user->id: 1],
            [['usuario_modificador'], 'filter', 'filter' => function(){return Yii::$app->user->id;},'when' => function($model){return !$model->isNewRecord;}],
            [['ip_log'], 'filter', 'filter' => function(){return ObtenerLogSeguridad::getRealIpAddr();}],
            [['id_ciudad'], 'exist', 'skipOnError' => true, 'targetClass' => Ciudades::className(), 'targetAttribute' => ['id_ciudad' => 'id_ciudad']],
            [['id_genero'], 'exist', 'skipOnError' => true, 'targetClass' => Genero::className(), 'targetAttribute' => ['id_genero' => 'id_genero']],
            [['id_nacionalidad'], 'exist', 'skipOnError' => true, 'targetClass' => Nacionalidad::className(), 'targetAttribute' => ['id_nacionalidad' => 'id_nacionalidad']],
            [['id_parroquia'], 'exist', 'skipOnError' => true, 'targetClass' => Parroquias::className(), 'targetAttribute' => ['id_parroquia' => 'id_parroquia']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_persona' => Yii::t('app', 'Persona'),
            'id_genero' => Yii::t('app', 'Genero'),
            'id_nacionalidad' => Yii::t('app', 'Nacionalidad'),
            'id_estado' => Yii::t('app', 'Estado'),
            'id_municipio' => Yii::t('app', 'Municipio'),
            'id_parroquia' => Yii::t('app', 'Parroquia'),
            'id_ciudad' => Yii::t('app', 'Ciudad'),
            'cedula' => Yii::t('app', 'Cédula'),
            'primer_nombre' => Yii::t('app', 'Primer Nombre'),
            'segundo_nombre' => Yii::t('app', 'Segundo Nombre'),
            'primer_apellido' => Yii::t('app', 'Primer Apellido'),
            'segundo_apellido' => Yii::t('app', 'Segundo Apellido'),
            'fecha_nacimiento' => Yii::t('app', 'Fecha Nacimiento'),
            'telefono_contacto' => Yii::t('app', 'Telefono Contacto'),
            'correo_electronico' => Yii::t('app', 'Correo Electronico'),
            'ip_log' => Yii::t('app', 'Ip Log'),
            'usuario_creador' => Yii::t('app', 'Usuario Creador'),
            'usuario_modificador' => Yii::t('app', 'Usuario Modificador'),
            'fecha_creacion' => Yii::t('app', 'Fecha Creacion'),
            'fecha_modificacion' => Yii::t('app', 'Fecha Modificacion'),
            'estatus' => Yii::t('app', 'Estatus'),
        ];
    }
    /*Relaciones*/
    
    public function getGenero(){
        return $this->hasOne(Genero::className(),['id_genero' => 'id_genero']);
    }
    
    public function getNacionalidad(){
        return $this->hasOne(Nacionalidad::className(),['id_nacionalidad' => 'id_nacionalidad']);
    }
    
    public function getCiudad(){
        return $this->hasOne(Ciudades::className(),['id_ciudad' => 'id_ciudad']);
    }

    public function getParroquia(){
        return $this->hasOne(Parroquias::className(),['id_parroquia' => 'id_parroquia']);
    }

    public function getMunicipio(){
        return $this->hasOne(Municipios::className(),['id_municipio' => 'id_municipio']);
    }

    public function getEstado(){
        return $this->hasOne(Estados::className(),['id_estado' => 'id_estado']);
    }
}
