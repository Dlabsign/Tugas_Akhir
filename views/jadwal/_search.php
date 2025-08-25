<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\JadwalSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="jadwal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'matakuliah_id') ?>

    <?= $form->field($model, 'jumlah_peserta') ?>

    <?= $form->field($model, 'laboratorium_id') ?>

    <?= $form->field($model, 'tanggal_jadwal') ?>

    <?php // echo $form->field($model, 'waktu_mulai') ?>

    <?php // echo $form->field($model, 'waktu_selesai') ?>

    <?php // echo $form->field($model, 'dibuat_oleh_staff_id') ?>

    <?php // echo $form->field($model, 'flag') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
