<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MapAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/map/leaflet.css',
        'css/map/Control.FullScreen.css',
        'css/map/easy-button.css',
        'css/map/mapa.css',   
    ];
    public $js = [
        'js/map/leaflet.js',
        'js/map/Control.FullScreen.js',
        'js/map/easy-button.js',
        'js/map/leaflet-easyPrint.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'rmrevin\yii\fontawesome\NpmFreeAssetBundle'
    ];
}
