<?php

/**
 * @var $this \yii\web\View
 * */ 
/**
 * @var $content string
 * */ 

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use app\assets\AppAsset;
use app\assets\MainAsset;
use app\models\Users;

AppAsset::register($this);
MainAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?php $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => 'Icon.png']); ?>
</head>
<body>
<?php $this->beginBody() ?>
    <header class=" mb-5">
        <h1 class="d-none text">Главная Страница</h1>
        <div class="container-xl d-flex flex-column flex-xl-row  align-items-center justify-content-between pt-3 pb-3">
            <div class="logo mb-xl-0 mb-5">
                <a height="100%" href="<?=BaseUrl::home() ?>"><img width="200px" src="<?=BaseUrl::home()?>assets/Logo.png" alt="Лого"></a>
            </div>
            <form class="d-flex align-items-center mb-xl-0 mb-5 position-relative">
                <label class="svg_search position-absolute" for="search"><img class="ml-2 mt-2" width="36px" src="<?=BaseUrl::home()?>assets/search.svg" alt="svg"></label>
                <input id="search" class="search form-control pl-5" type="search" placeholder="Введите название темы" aria-label="Search">
            </form>
            <div class="account d-flex align-items-center mb-xl-0 mb-3">
                <?=
                    Yii::$app->user->isGuest ? (
                        Html::a('Log In', BaseUrl::to(['/site/login'], true), ['class' => 'login btn btn-outline-light mr-4 ml-4 text']) 
                        . Html::a('Sign Up', BaseUrl::to(['/site/register'], true), ['class' => 'register  btn btn-outline-warning text'])      
                    ) : (
                        ' <div class="dropdown">'
                        . '<a class="text btn btn-outline-warning dropdown-toggle profile" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                        . '<img class="ellipse" src="' . BaseUrl::home() . Users::findOne(['username' => Yii::$app->user->identity->username])->image . '" alt="svg">'
                        . Yii::$app->user->identity->username
                        . '</a>'
                        . Html::beginTag('div', ['class' => 'dropdown-menu', 'aria-labelledby' => 'dropdownMenuLink'])
                        . Html::a('Профиль', BaseUrl::to(['/site/profile'], true), ['class' => 'dropdown-item'])
                        . Html::a('Настройки', BaseUrl::to(['/site/settings'], true), ['class' => 'dropdown-item'])
                        . Html::beginForm(['/site/logout'], 'post', ['class' => 'drpdown-item logout-form'])
                        . Html::submitButton(
                            'Выйти',
                            ['class' => 'text logout']
                        )
                        . Html::endForm()
                        . Html::endTag('div')
                        . '</div>'
                    )
                ?>
                
                
            </div>
        </div>
    </header>

    <main class="container-xl">
        <?= $content ?>
    </main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
