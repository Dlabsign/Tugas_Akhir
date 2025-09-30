<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Jadwal;

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var app\models\Detail_soal[] $modelsSoal */
/** @var app\models\Soal $modelUtama */

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

    <!-- header: sesi_id dan kode_soal -->
    <?= $form->field($modelUtama, "sesi_id")
        ->dropDownList($jadwal, ['prompt' => 'Pilih Jadwal', 'id' => 'sesi-id']) ?>

    <?= $form->field($modelUtama, "kode_soal")
        ->textInput(['maxlength' => true, 'id' => 'kode-soal']) ?>

    <div id="soal-container">
        <?php foreach ($modelsSoal as $i => $soal): ?>
            <div class="soal-item border p-3 mb-3">
                <input type="hidden" name="Detail_soal[<?= $i ?>][id]" value="<?= $soal->id ?>">
                <div class="form-group">
                    <label>Teks Soal</label>
                    <textarea name="Detail_soal[<?= $i ?>][teks_soal]" class="form-control" rows="3" required><?= $soal->teks_soal ?></textarea>
                </div>
                <div class="form-group">
                    <label>Skor Maks</label>
                    <input type="number" step="0.01" name="Detail_soal[<?= $i ?>][skor_maks]" class="form-control" required value="<?= $soal->skor_maks ?>">
                </div>
                <input type="hidden" name="Detail_soal[<?= $i ?>][sesi_id]" value="<?= $soal->sesi_id ?>">
                <input type="hidden" name="Detail_soal[<?= $i ?>][kode_soal]" value="<?= $soal->kode_soal ?>">
                <button type="button" class="btn btn-danger btn-sm remove-soal">Hapus</button>
            </div>
        <?php endforeach; ?>
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
let index = $('#soal-container .soal-item').length; // mulai dari jumlah soal awal

function getHeaderSesi() {
    return $('#sesi-id').val() || '';
}
function getHeaderKode() {
    return $('#kode-soal').val() || '';
}

// tambah soal
$('#add-soal').on('click', function() {
    let sesiVal = getHeaderSesi();
    let kodeVal = getHeaderKode();

    let newItem = `
        <div class="soal-item border p-3 mb-3">
            <input type="hidden" name="Detail_soal[${index}][id]" value="">
            <div class="form-group">
                <label>Teks Soal</label>
                <textarea name="Detail_soal[${index}][teks_soal]" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Skor Maks</label>
                <input type="number" step="0.01" name="Detail_soal[${index}][skor_maks]" class="form-control" required>
            </div>
            <input type="hidden" name="Detail_soal[${index}][sesi_id]" value="${sesiVal}">
            <input type="hidden" name="Detail_soal[${index}][kode_soal]" value="${kodeVal}">
            <button type="button" class="btn btn-danger btn-sm remove-soal">Hapus</button>
        </div>
    `;
    $('#soal-container').append(newItem);
    index++; // counter selalu naik
});

// hapus soal
$(document).on('click', '.remove-soal', function() {
    $(this).closest('.soal-item').remove();
});
JS;

$this->registerJs($script);
?>




<!-- // $script = <<<'JS'
// let index = $('#soal-container .soal-item').length;

// let newItem = `
//     <div class="soal-item border p-3 mb-3">
//         <input type="hidden" name="Detail_soal[${index}][id]" value="">
//         <div class="form-group">
//             <label>Teks Soal</label>
//             <textarea name="Detail_soal[${index}][teks_soal]" class="form-control" rows="3" required></textarea>
//         </div>
//         <div class="form-group">
//             <label>Skor Maks</label>
//             <input type="number" step="0.01" name="Detail_soal[${index}][skor_maks]" class="form-control" required>
//         </div>
//         <input type="hidden" name="Detail_soal[${index}][sesi_id]" value="${sesiVal}">
//         <input type="hidden" name="Detail_soal[${index}][kode_soal]" value="${kodeVal}">
//         <button type="button" class="btn btn-danger btn-sm remove-soal">Hapus</button>
//     </div>
// `;
// $('#soal-container').append(newItem);
// JS; -->