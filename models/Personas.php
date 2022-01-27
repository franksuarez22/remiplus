<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "incidencias.r_personas".
 *
 * @property int $id_persona N°
 * @property int $id_genero Género
 * @property int $id_nacionalidad Nacionalidad
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
            [['id_genero', 'id_nacionalidad', 'id_parroquia', 'id_ciudad', 'cedula', 'primer_nombre', 'primer_apellido', 'telefono_contacto', 'correo_electronico', 'ip_log', 'usuario_creador', 'fecha_creacion'], 'required'],
            [['id_genero', 'id_nacionalidad', 'id_parroquia', 'id_ciudad', 'cedula', 'usuario_creador', 'usuario_modificador'], 'default', 'value' => null],
            [['id_genero', 'id_nacionalidad', 'id_parroquia', 'id_ciudad', 'cedula', 'usuario_creador', 'usuario_modificador'], 'integer'],
            [['fecha_nacimiento', 'fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['ip_log'], 'string'],
            [['estatus'], 'boolean'],
            [['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'correo_electronico'], 'string', 'max' => 150],
            [['telefono_contacto'], 'string', 'max' => 11],
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
            'id_persona' => 'N°',
            'id_genero' => 'Género',
            'id_nacionalidad' => 'Nacionalidad',
            'id_parroquia' => 'Parroquia de recidencia',
            'id_ciudad' => 'Ciudad de recidencia',
            'cedula' => 'Cédula',
            'primer_nombre' => 'Primer nombre',
            'segundo_nombre' => 'Segundo nombre',
            'primer_apellido' => 'Primer apellido',
            'segundo_apellido' => 'Segundo apellido',
            'fecha_nacimiento' => 'Fecha de nacimiento',
            'telefono_contacto' => 'Teléfono de contacto',
            'correo_electronico' => 'Correo electrónico',
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
     * @return PersonasQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PersonasQuery(get_called_class());
    }
}
