<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Jadwal;

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
$this->params['breadcrumbs'][] = $this->title;

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

    <?= $form->field($modelsSoal[0], 'sesi_id')->dropDownList($jadwal, ['prompt' => 'Pilih Sesi']) ?>
    <?= $form->field($modelsSoal[0], 'kode_soal')->textInput(['maxlength' => true]) ?>
    <?= $form->field($modelsSoal[0], 'bahasa')->radioList([
        1 => 'Mysql',
        2 => 'C++',
    ]) ?>

    <!-- Container soal -->
    <div id="soal-container">
        <div class="soal-item border p-3 mb-3">
            <div class="form-group">
                <label>Tipe Soal:</label><br>
                <label>
                    <input type="radio" name="soal[0][type]" value="1" checked> Kode
                </label>
                &nbsp;&nbsp;
                <label>
                    <input type="radio" name="soal[0][type]" value="2"> Isian
                </label>
            </div>

            <!-- Textarea soal -->
            <div class="form-group">
                <label>Soal</label>
                <textarea name="soal[0][teks_soal]" class="form-control" rows="3" required></textarea>
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
                <label>Tipe Soal:</label><br>
                <label>
                    <input type="radio" name="soal[\${index}][type]" value="1" checked> Kode
                </label>
                &nbsp;&nbsp;
                <label>
                    <input type="radio" name="soal[\${index}][type]" value="2"> Isian
                </label>
            </div>

            <div class="form-group">
                <label>Teks Soal</label>
                <textarea name="soal[\${index}][teks_soal]" class="form-control" rows="3" required></textarea>
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
// Ganti tampilan input berdasarkan radio button
$(document).on('change', '.type-radio', function() {
    let soalItem = $(this).closest('.soal-item');
    let selectedType = $(this).val();

    if (selectedType == '1') {
        soalItem.find('.soal-textarea').removeClass('d-none');
        soalItem.find('.soal-input').addClass('d-none');
    } else {
        soalItem.find('.soal-textarea').addClass('d-none');
        soalItem.find('.soal-input').removeClass('d-none');
    }
});
JS;

$this->registerJs($script);
