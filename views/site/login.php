<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\BaseUrl;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form ActiveForm */
$this->title = 'WebGremlins Forum';
?>
<div class="site-login">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
    
        <div class="form-group">
            <?= Html::submitButton('Log In', ['class' => 'btn btn-primary']) ?>
            <?= '<p class="text"> Нет аккаунта? ' . Html::a("Зарегистрируйтесь!", BaseUrl::to(['site/register'], true, ['class' => 'text'])) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-login -->
