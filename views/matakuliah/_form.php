<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Matakuliah $model */
?>

<div class="matakuliah-form">

    <?php $form = ActiveForm::begin([
        'id' => 'matakuliah-form',
        'enableAjaxValidation' => false,
    ]); ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'nama')->textInput(['maxlength' => true])->label('Nama Mata Kuliah') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'semester')->textInput() ?>
        </div>
    </div>

    <?= Html::button(
        $model->isNewRecord ? 'Simpan' : 'Perbarui',
        ['id' => 'btn-save', 'class' => 'btn btn-primary w-100 mt-3']
    ) ?>

    <?php ActiveForm::end(); ?>
</div>

<?php
$createUrl = Url::to(['matakuliah/create']);
$updateUrl = Url::to(['matakuliah/update']);

$js = <<<JS
$('#btn-save').off('click').on('click', function(e) {
    e.preventDefault();

    var form = $('#matakuliah-form');
    var id = form.find('#matakuliah-id').val();
    var url = id ? '$updateUrl?id=' + id : '$createUrl';

    $.ajax({
        url: url,
        type: 'POST',
        data: form.serialize(),
        success: function(res) {
            if (res.success) {
                // Tutup modal langsung tanpa alert
                $('#modalCreate').modal('hide');
                // Refresh GridView (jika pakai Pjax)
                if ($.pjax) {
                    $.pjax.reload({container: '#w0-pjax'});
                } else {
                    // Kalau gak pakai Pjax, reload full page
                    location.reload();
                }
            } else if (res.errors) {
                // Render ulang form kalau ada error
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
