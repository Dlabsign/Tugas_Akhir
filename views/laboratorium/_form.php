<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="laboratorium-form">
    <?php $form = ActiveForm::begin([
        'id' => 'laboratorium-form',
        'enableAjaxValidation' => false,
        'options' => ['onsubmit' => 'return false;'], // cegah submit default
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'ruang')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-success', 'id' => 'btn-save']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$createUrl = \yii\helpers\Url::to(['laboratorium/create']);
$js = <<<JS
$('#btn-save').on('click', function(e) {
    e.preventDefault();
    var form = $('#laboratorium-form');
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