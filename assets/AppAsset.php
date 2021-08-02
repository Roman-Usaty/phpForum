<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{

    // Все манипульяции с ассетами производить ТОЛЬКО в MainAsset.php файле. Этот файл служит лишь как загрщик jquery и bootstrap в head на странице
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset'
    ];
}
