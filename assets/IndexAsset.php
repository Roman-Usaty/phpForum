<?php

namespace app\assets;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle 
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/index.css'
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset'
    ];
}