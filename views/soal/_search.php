<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SoalSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="soal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'jadwal_id') ?>

    <?= $form->field($model, 'matakuliah_id') ?>

    <?= $form->field($model, 'soal_id') ?>

    <?= $form->field($model, 'bobot_soal') ?>

    <?php // echo $form->field($model, 'kode_soal') ?>

    <?php // echo $form->field($model, 'teks_soal') ?>

    <?php // echo $form->field($model, 'skor_maks') ?>

    <?php // echo $form->field($model, 'flag') ?>

    <?php // echo $form->field($model, 'nama_file') ?>

    <?php // echo $form->field($model, 'data') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
