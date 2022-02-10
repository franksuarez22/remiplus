<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Usuario;
use app\components\ComplementFunctions as cf;
use app\models\Empresaseniat;
use yii\helpers\Url;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $rif;
    public $captcha;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\Usuario', 'message' => 'Este correo ya está registrado.'],

            ['rif', 'required'],
            ['rif', 'string', 'min' => 10],
            ['rif', 'string', 'max' => 10],
            ['rif', 'unique', 'targetClass' => '\app\models\Usuario', 'message' => 'Este RIF ya está registrado.'],

            ['captcha', 'captcha'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $empresa = Empresaseniat::findByRif(mb_strtoupper($this->rif,'UTF-8'));
        if(isset($empresa) && !empty($empresa) && !is_null($empresa)){
            $names = $empresa->razonsocial;
        }else{
	    $url = Url::to(['/rifsolicitantes/create']);
	    if ($_SERVER['SERVER_NAME'] == 'localhost') {
		$servername = $_SERVER['SERVER_NAME'] . Yii::app()->baseUrl . $url;
	    } else {
		$servername = $_SERVER['SERVER_NAME'] . $url;
	    }
            Yii::$app->session->setFlash('danger', "Rif no encontrado.\n Debe crear una solicitud en el siguiente enlace $servername");
            return;
        }
        
        $usuario = $this->generateNewUsername($this->email);
        $password = substr(md5($this->rif), 0, rand ( 8 , 12 ));
        
        $user = new Usuario();
        $user->username = $usuario;
        $user->email = $this->email;
        $user->names = $names;
        $user->rif = mb_strtoupper($this->rif,'UTF-8');
        $user->setPassword($password);
        $user->no_encrypted_password = $password;
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->validate();
        if ($user->save() && $this->issendEmail($user)) {
            $auth = \Yii::$app->authManager;
            $authorRole = $auth->getRole('informante');
            $auth->assign($authorRole, $user->id);
            return true;
        }
        
        return false;
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function issendEmail($user)
    {
        return Yii::$app
            ->mailer/*->compose()*/
            ->compose(
                ['html' => 'passwordSend-html', 'text' => 'passwordSend-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Registro del usuario  ' . Yii::$app->name)
            ->send();
    }

    /**
     * generateNewUsername
     *     genera un username no existente agregando un ".NNNN" al final del email recortado hasta su arroba.
     * @param mixed $emailBased email del cual basarse.
     * @access public
     * @return void un username no existente. ejmplo: micorreo.8217i
     *
     *    importante:
     *        si el correo como tal (cortado hasta su arroba) no existe al
     *        buscar un usuario por su username, pues se usará, luego
     *        se le ira agregando un numero secuencial hasta dar con un username
     *        que no exista.
     */
    public function generateNewUsername($emailBased)
    {
        $username = strtolower(substr($emailBased, 0, strpos($emailBased, '@')));
        
        $usuario = $username;
        $ok = false;
        $sec = 1;
        do {
            $u = $this->loadUserByUsername($username);
            if ($u == null) {
                // no existe, lo usamos.
                $ok = true;
            } else {
                // si existe, anexamos un numero secuencial
                $username = $usuario . $sec;
                $sec++;
            }
        } while (!$ok);
        return $username;
    }

    /**
     * loadUserByUsername
     *    busca un usuario por su username exclusivamente.
     * @param mixed $username
     * @param mixed $boolAndLoadFields flag, true para precargar campos personalizados.
     * @access public
     * @return instancia de CrugeStoredUser
     */
    public function loadUserByUsername($username)
    {
        $user = Usuario::findByUserName($username);

        return $user;
    }
}
