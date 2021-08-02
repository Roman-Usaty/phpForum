<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\BaseUrl;
use app\assets\ProfileAsset;


/* @var $this yii\web\View */
/* @var $model app\models\Users*/
/* @var $form ActiveForm */
$this->title = 'WebGremlins Forum | Настройки профиля';
ProfileAsset::register($this);
?>
<div class="site-settings">
    
    <?php $form = ActiveForm::begin([
        'options' => ['data' => ['pjax' => true]]
    ]); ?>
        <div class="profile d-flex flex-column align-items-center m-auto">
            <img class="profile__image mb-5" src="<?=BaseUrl::to(['/' . $model->image], true)?>" alt="Аватар">
            <?=$form->field($model, 'imageFile')->fileInput()?>
            <!-- <?= Html::input('file', 'Users[imageFile]', '', ['accept' => 'image/png,image/jpeg,image/gif', 'class' => 'd-none', 'id' => 'fileimage'] )?>
            <label class="choose_image btn-primary" for="fileimage">Загрузить файл</label>  -->
            <div class="profile__name d-flex flex-column mt-5 mb-5">
               <?=$form->field($model, 'username')->textInput()?>
            </div>
            <div class="profile__description position-relative d-flex flex-column">
                <span class="mb-2">About</span>
                <textarea maxlength="1024" name="Users[about]" id="textAbout" cols="30" rows="10" ></textarea>
                <script>$(function(){var text="<?=$model->about?>";$("#textAbout").text(text);});</script>
                <p class="description_maxvalue">Осталось символов <span id="counter_description">0</span> из 1024</p>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-outline-primary btn-save']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>