<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "public.usuario".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $names
 * @property string $email
 * @property string $rif
 * @property bool $status
 */
class Usuario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'public.usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'names', 'email', 'rif'], 'required'],
            [['status'], 'boolean'],
            [['username', 'password', 'names', 'email'], 'string', 'max' => 255],
            [['rif'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'names' => 'Names',
            'email' => 'Email',
            'rif' => 'Rif',
            'status' => 'Status',
        ];
    }

    /**
     * {@inheritdoc}
     * @return UsuarioQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsuarioQuery(get_called_class());
    }
}
