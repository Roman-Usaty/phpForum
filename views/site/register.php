<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\BaseUrl;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form ActiveForm */
$this->title = 'WebGremlins Forum';
?>
<div class="site-index">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 're_pass')->passwordInput()  ?>
    
        <div class="form-group">
            <?= Html::submitButton('Sign Up', ['class' => 'btn btn-primary']) ?>
            <?= '<p class="text"> Уже есть аккаунт? ' . Html::a("Войдите!", BaseUrl::to(['site/login'], true, ['class' => 'text'])) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-index -->