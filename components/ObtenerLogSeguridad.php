<?php

/*
 * Realidado por: Rafael A. Rojas
 * Fecha Realización/Modificación: 20/04/2015
 * MPPDME
 */

namespace app\components;

use Yii;
use yii\db\Expression;

class ObtenerLogSeguridad {

    public $logSeguridad; // configurable en config/main.php 

    public function init() {
        // init es llamado por Yii, debido a que es un componente.
    }

    public function getRealIpAddr() {
        $informe_ip = '';
        $remote_ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '(Sin IP)';
        $remote_isp = gethostbyaddr($remote_ip);
        $informe_ip = $remote_ip . ' ' . $remote_isp;
        $referer = (isset($_SERVER['HTTP_REFERER'])) ? strtolower($_SERVER['HTTP_REFERER']) : 'error';
        $informe_ip = $informe_ip . ' ' . $referer;
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            $informe_ip = $informe_ip . ' ' . 'HTTP_CLIENT_IP ' . $_SERVER['HTTP_CLIENT_IP'];
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $informe_ip = (string) $informe_ip . ' ' . 'HTTP_X_FORWARDED_FOR ' . $_SERVER['HTTP_X_FORWARDED_FOR'];
        return $informe_ip;
    }
    
    public function cdbexpression() {
        $expression = new Expression('NOW()');
        $now = (new \yii\db\Query)->select($expression)->scalar();  // SELECT NOW();
        return $now; // prints the current date
    }

}
