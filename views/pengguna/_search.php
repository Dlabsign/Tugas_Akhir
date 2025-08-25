<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PenggunaControllerSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengguna-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'password') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'semester') ?>

    <?php // echo $form->field($model, 'lab') ?>

    <?php // echo $form->field($model, 'dibuat_pada') ?>

    <?php // echo $form->field($model, 'diperbarui_pada') ?>

    <?php // echo $form->field($model, 'flag') ?>

    <?php // echo $form->field($model, 'nim') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
