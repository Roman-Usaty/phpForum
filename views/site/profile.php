<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use app\assets\ProfileAsset;

ProfileAsset::register($this);  
$this->title = "WebGremlins | Профиль";
?>

<div class="d-flex justify-content-around">
    <div class="profile__image d-flex flex-column">
        <img class="mb-4" src="<?=BaseUrl::to(['/' . $model->image], true)?>" alt="">
        <span><?=Yii::$app->user->identity->username?></span>
    </div>
    <div class="profile__description">
        <h2>Информация</h2>
        <hr>
        <p><?=$model->about?></p>
        <a class="description__link" href="#">Раскрыть полное описание 
            <svg width="10" height="8" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 8L0.669872 0.5H9.33013L5 8Z" fill="316FB7"/>
            </svg>
        </a>
    </div>
</div>