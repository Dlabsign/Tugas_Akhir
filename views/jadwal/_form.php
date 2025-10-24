<?php

use app\models\Laboratorium;
use app\models\Matakuliah;
use app\models\Pengguna;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$labs = ArrayHelper::map(Laboratorium::find()->where(['flag' => 1])->all(), 'id', 'nama');
$staff = ArrayHelper::map(Pengguna::find()->where(['flag' => 1])->all(), 'id', 'username');
$matkul = ArrayHelper::map(Matakuliah::find()->where(['flag' => 1])->all(), 'id', 'nama');
?>

<div class="jadwal-form">
    <?php $form = ActiveForm::begin([
        'id' => 'jadwal-form',
        'enableAjaxValidation' => false,
    ]); ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'sesi')->label('Masukkan Sesi')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'laboratorium_id')->dropDownList($labs, ['prompt' => '-- Pilih Laboratorium --']) ?>
        </div>
    </div>

    <hr><br>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'tanggal_jadwal')->label('Tanggal Jadwal')->input('date') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'waktu_mulai')->label('Waktu Mulai')->input('time') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'waktu_selesai')->label('Waktu Selesai')->input('time') ?>
        </div>
    </div>

    <hr><br>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'dibuat_oleh_staff_id')->dropDownList($staff, ['prompt' => '-- Pilih Staff --']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'matakuliah_id')->dropDownList($matkul, ['prompt' => '-- Pilih Matakuliah --']) ?>
        </div>
    </div>

    <div class="mt-3">
        <?= Html::button(
            $model->isNewRecord ? 'Simpan' : 'Perbarui',
            ['id' => 'btn-save', 'class' => 'btn btn-primary w-100 mt-3']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$createUrl = Url::to(['jadwal/create']);
$updateUrl = Url::to(['jadwal/update']);

$js = <<<JS
$('#btn-save').off('click').on('click', function(e) {
    e.preventDefault();

    var form = $('#jadwal-form');
    var id = form.find('#jadwal-id').val();
    var url = id ? '$updateUrl?id=' + id : '$createUrl';

    $.ajax({
        url: url,
        type: 'POST',
        data: form.serialize(),
        success: function(res) {
            if (res.success) {
                // Tutup modal langsung tanpa notifikasi
                $('#modalCreate').modal('hide');
                // Reload tabel (jika pakai Pjax)
                if ($.pjax) {
                    $.pjax.reload({container: '#w0-pjax'});
                } else {
                    location.reload();
                }
            } else if (res.errors) {
                let msg = Object.values(res.errors).map(err => err[0]).join('\\n');
                alert('Gagal menyimpan data:\\n' + msg);
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