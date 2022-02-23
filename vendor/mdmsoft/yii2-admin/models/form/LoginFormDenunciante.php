<?php

namespace mdm\admin\models\form;

use Yii;
use yii\base\Model;
use mdm\admin\models\User;
use yii\helpers\Url;
/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LoginFormDenunciante extends Model
{

public function scenarios()
{
    $scenarios = parent::scenarios();
    $scenarios['verifytoken'] = ['id_nacionalidad','cedula', 'telefono', 'correo', 'token'];
    return $scenarios;
}

    public $id_nacionalidad;
    public $cedula;
    public $telefono;
    public $correo;
    public $token;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['id_nacionalidad', 'cedula', 'telefono', 'correo'], 'required'],
            //[['token'], 'safe', 'on' => 'verifytoken'],
            [['token'], 'required', 'on' => 'verifytoken'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            [['cedula'], 'validaCedula'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validaCedula($attribute, $params) {
        if (!empty($attribute)) {

            if (!empty($this->cedula) && !empty($this->id_nacionalidad)) {
                    $persona = \app\models\Persona::find()->where([
                        'cedula' => $this->cedula, 
                        //'nacionalidad' => $this->id_nacionalidad==1?""
                    ])->one();             
                    if (!isset($persona) || empty($persona) || is_null($persona)) {
                    $this->addError($attribute, 'Número de cédula invalido');
                }
            }
        }
    }
    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {   
        $usuario = $this->getUser(); // Se agrega esta linea
        if ($this->validate()) {
            if($usuario){ // Se agrega este if 
                return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
            }                                    
        }
        /*if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), 0);
        }*/
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByCedula($this->cedula);
        }

        return $this->_user;
    }

     /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'id_nacionalidad' => 'Nacionalidad',
            'cedula' => 'Cédula de identidad',
            'telefono' => 'Número teléfonico',
            'correo' => 'Correo electrónico',
            'token' => 'Token',
            'captcha' => 'Captcha',
            
        ];
    }
}
