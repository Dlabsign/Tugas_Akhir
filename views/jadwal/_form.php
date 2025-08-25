<?php

use app\models\Laboratorium;
use app\models\Matakuliah;
use app\models\Pengguna;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$labs = ArrayHelper::map(Laboratorium::find()->all(), 'id', 'nama');
$staff = ArrayHelper::map(Pengguna::find()->all(), 'id', 'username');
$matkul = ArrayHelper::map(Matakuliah::find()->all(), 'id', 'nama');
?>

<div class="jadwal-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'jumlah_peserta')->label('Jumlah Mahasiswa')->textInput() ?>
    <?= $form->field($model, 'laboratorium_id')->dropDownList(
        $labs,
        ['prompt' => '-- Pilih Laboratorium --']
    ); ?>
    <?= $form->field($model, 'tanggal_jadwal')->label('Tanggal Mulai')->textInput(['type' => 'date']) ?>
    <?= $form->field($model, 'waktu_mulai')->input('time')->label('Waktu Mulai') ?>
    <?= $form->field($model, 'waktu_selesai')->input('time')->label('Waktu Selesai') ?>
    <?= $form->field($model, 'dibuat_oleh_staff_id')->dropDownList(
        $staff,
    ); ?>
    <?= $form->field($model, 'matakuliah_id')->dropDownList(
        $matkul,
    ); ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>