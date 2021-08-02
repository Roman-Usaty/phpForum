<?php
namespace app\assets;

use yii\web\AssetBundle;

class MainAsset extends AssetBundle 
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/fonts/Proxima Soft/stylesheet.css',
        'css/header.css'
    ];
    public $js = [
        'scripts/vue.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset'
    ];
}