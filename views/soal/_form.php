<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Jadwal;

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */

$jadwal = ArrayHelper::map(
    Jadwal::find()->where(['flag' => 1])->with(['matakuliah', 'laboratorium'])->all(),
    'id',
    function ($model) {
        return $model->sesi . ' - '
            . ($model->matakuliah ? $model->matakuliah->nama : '(Mata Kuliah Tidak Ada)')
            . ' - '
            . ($model->laboratorium ? $model->laboratorium->ruang : '(Lab Tidak Ada)');
    }
);
?>

<div class="soal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($modelsSoal[0], 'sesi_id')->dropDownList($jadwal, ['prompt' => 'Pilih Jadwal']) ?>
    <?= $form->field($modelsSoal[0], 'kode_soal')->textInput(['maxlength' => true]) ?>


    <!-- Container soal -->
    <div id="soal-container">
        <div class="soal-item border p-3 mb-3">
            <div class="form-group">
                <label>Teks Soal</label>
                <textarea name="soal[0][teks_soal]" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Skor Maks</label>
                <input type="number" name="soal[0][skor_maks]" class="form-control" required>
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-soal">Hapus</button>
        </div>
    </div>

    <button type="button" id="add-soal" class="btn btn-success">Tambah Soal</button>
    <br><br>

    <div class="form-group">
        <?= Html::submitButton('Simpan Semua', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<'JS'
let index = 1;

// tambah soal
$('#add-soal').on('click', function() {
    let newItem = `
        <div class="soal-item border p-3 mb-3">
            <div class="form-group">
                <label>Teks Soal</label>
                <textarea name="soal[${index}][teks_soal]" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Skor Maks</label>
                <input type="number" name="soal[${index}][skor_maks]" class="form-control" required>
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-soal">Hapus</button>
        </div>
    `;
    $('#soal-container').append(newItem);
    index++;
});

// hapus soal
$(document).on('click', '.remove-soal', function() {
    $(this).closest('.soal-item').remove();
});
JS;

$this->registerJs($script);
