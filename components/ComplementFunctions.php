<?php

namespace app\components;
use yii\helpers\Html;
use Yii;

/**
 * Description of ComplementFunctions
 *
 * @author fsuarez
 */
class ComplementFunctions {

    public function init() {
        // init es llamado por Yii, debido a que es un componente.
    }

    /**
     * Imprime por pantalla el contenido de un arreglo o modelo
     * @param array/model $arreglo
     * @param boolean $die
     */
    public static function iam($arreglo, $die = false) {
        echo '<pre>';
        print_r($arreglo);
        echo '<pre>';
        if ($die === true)
            die;
    }
    
    public static function rolUsuario(){
        $rol = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
        $nrol = [];
        foreach ($rol as $key => $value) {
            $nrol[]=$value->name;
        }   
        return $nrol; 
    }

    public static function datosConfiguracion(){
        $Configuracion = \app\models\Configuracion::findOne(['estatus' => true]);
        if(isset($Configuracion) && !empty($Configuracion) && !is_null($Configuracion)){
            return $Configuracion;        
        }else{
            die("Sin datos de configuración");
        } 
    }

    public static function generarToken(){
        $token = bin2hex(random_bytes(3)); 
        $Tokensesion = \app\models\Tokensesion::findOne([
            'token_sesion' => $token, 
            'estatus' => true
        ]);
        if(isset($Tokensesion) && !empty($Tokensesion) && !is_null($Tokensesion)){
            ComplementFunctions::generarToken();        
        } 
        return $token;
    }

    public static function tokenValido($model){
        $valido["valido"]=false;
        $t = \app\models\Tokensesion::find([
            'cedula' => $model->cedula, 
            'telefono_contacto' => $model->telefono, 
            'correo_electronico' => $model->correo, 
            'token_sesion' => $model->token, 
            'estatus' => true
        ])->max('id_token_sesion');
        if(isset($t) && !empty($t) && !is_null($t)){
            $Tokensesion = \app\models\Tokensesion::findOne([
                'id_token_sesion' => $t, 
            ]);
        }
        //ComplementFunctions::iam(["t".$t,$Tokensesion,$model],true);
        if(isset($Tokensesion) && !empty($Tokensesion) && !is_null($Tokensesion)){
            $fecha_actual = date ( 'Y-m-d H:i:s' , intval(microtime(true)));
            $fecha_expiracion = date ( 'Y-m-d H:i:s' , $Tokensesion->fecha_expiracion);
            //ComplementFunctions::iam([$Tokensesion, $model, $fecha_actual, $fecha_expiracion],true);
            if ($fecha_expiracion > $fecha_actual) {
                $valido["valido"]=true;
                $valido["vencido"]=false;
                $valido["mensaje"]="";
                $valido["token"]=$Tokensesion->token_sesion;
            }else{
                $Tokensesion->estatus=false;
                $Tokensesion->update();
                $valido["valido"]=false;
                $valido["vencido"]=true;
                $valido["mensaje"]="Token vencido";
            }
        }else{
            $valido["valido"]=false;
            $valido["vencido"]=false;
            $valido["mensaje"]="Token incorrecto";
        } 
        return $valido;  
    }

    public static function enviarToken($model, $reenviar=false){
        $configuracion = ComplementFunctions::datosConfiguracion();
        $fecha_actual = intval(microtime(true));
        /*
        ComplementFunctions::iam([
            date ( 'Y-m-d H:i:s' , $fecha_actual), date_default_timezone_get(), 
            ini_get('date.timezone'),
            date ( 'Y-m-d H:i:s' ,($fecha_actual + (60 * $configuracion->vencimiento_token_sesion)))
        ],true);*/
        $Tokensesion = ComplementFunctions::tokenValido($model);
        if($Tokensesion["valido"]==false){

            $newToken= new \app\models\Tokensesion;
            $newToken->token_sesion = ComplementFunctions::generarToken();
            $newToken->cedula = $model->cedula;
            $newToken->telefono_contacto = $model->telefono;
            $newToken->correo_electronico = $model->correo;
            $newToken->fecha_inicio = $fecha_actual;
            $newToken->fecha_expiracion = ($fecha_actual + (60 * $configuracion->vencimiento_token_sesion));
            $newToken->usuario_creador = 1;
            $newToken->validate();
            
            if($newToken->save(false)){
                $email = ComplementFunctions::issendEmail($newToken);
                return $newToken->token_sesion;
                //Envió de token por correo o sms
               
               //ComplementFunctions::iam($email,true);
            }
        }else{
            return $Tokensesion["token"];
            if($reenviar){
                //Envió de token por correo o sms
            }
        }    
    }

    protected static function issendEmail($model)
    {
        /*return Yii::$app
            ->mailer
            ->compose(
                ['html' => '@app/mail/passwordSend-html', 'text' => '@app/mail/passwordSend-text'],
                ['model' => $model]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($model->correo_electronico)
            ->setSubject('Registro del usuario  ' . Yii::$app->name)
            ->send();*/


            return Yii::$app
                ->mailer
                ->compose(
                    ['html' => '@app/mail/passwordSend-html', 'text' => '@app/mail/passwordSend-text'],
                    ['model' => $model]
                )
                ->setFrom(Yii::$app->params['senderEmail'])
                ->setTo($model->correo_electronico)
                ->setSubject('$form->subject')
                ->send();
    }

}
