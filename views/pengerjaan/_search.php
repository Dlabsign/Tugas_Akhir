<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PengerjaanSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengerjaan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'soal_id') ?>

    <?= $form->field($model, 'kode_soal') ?>

    <?= $form->field($model, 'mahasiswa_id') ?>

    <?= $form->field($model, 'waktu_pengumpulan') ?>

    <?php // echo $form->field($model, 'jawaban_teks') ?>

    <?php // echo $form->field($model, 'skor') ?>

    <?php // echo $form->field($model, 'umpan_balik') ?>

    <?php // echo $form->field($model, 'staff_check') ?>

    <?php // echo $form->field($model, 'flag') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
