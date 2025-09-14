<?php

use app\models\Laboratorium;
use app\models\Matakuliah;
use app\models\Pengguna;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$labs = ArrayHelper::map(Laboratorium::find()->where(['flag' => 1])->all(), 'id', 'nama');
$staff = ArrayHelper::map(Pengguna::find()->where(['flag' => 1])->all(), 'id', 'username');
$matkul = ArrayHelper::map(Matakuliah::find()->where(['flag' => 1])->all(), 'id', 'nama');
?>

<div class="jadwal-form">
    <?php $form = ActiveForm::begin([
        'id' => 'jadwal-form',
        'enableAjaxValidation' => false,
        'options' => ['onsubmit' => 'return false;'], // cegah submit default
    ]); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'sesi')->label('Masukkan Sesi')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'laboratorium_id')->dropDownList(
                $labs,
                ['prompt' => '-- Pilih Laboratorium --']
            ); ?>
        </div>
        <hr>
        <br>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'tanggal_jadwal')->label('Tanggal Mulai')->textInput(['type' => 'date']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'waktu_mulai')->input('time')->label('Waktu Mulai') ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'waktu_selesai')->input('time')->label('Waktu Selesai') ?>
            </div>
        </div>
        <hr>
        <br>
        <div class="row">
            <div class="col-md-6">

                <?= $form->field($model, 'dibuat_oleh_staff_id')->dropDownList(
                    $staff,
                ); ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'matakuliah_id')->dropDownList(
                    $matkul,
                ); ?>
            </div>


            <div class="form-group">
                <?= Html::submitButton('Simpan', ['class' => 'btn btn-success', 'id' => 'btn-save']) ?>

            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


<?php
$createUrl = \yii\helpers\Url::to(['jadwal/create']);
$js = <<<JS
$('#btn-save').on('click', function(e) {
    e.preventDefault();
    var form = $('#jadwal-form');
    $.ajax({
        url: '$createUrl',
        type: 'POST',
        data: form.serialize(),
        success: function(res) {
            if (res.success) {
                alert('Data berhasil disimpan!');
                location.reload();
            } else {
                if (res.errors && res.errors.nama) {
                    alert(res.errors.nama[0]); // tampilkan pesan unik
                } else {
                    alert('Gagal menyimpan data!');
                }
            }
        },
        error: function() {
            alert('Terjadi kesalahan server.');
        }
    });
});
JS;
$this->registerJs($js);
?>