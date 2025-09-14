<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use app\models\Jadwal;

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

<?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper',
    'widgetBody' => '.container-items',
    'widgetItem' => '.item',
    'limit' => 10, // maksimal 10 soal
    'min' => 1, // minimal 1 soal
    'insertButton' => '.add-item',
    'deleteButton' => '.remove-item',
    'model' => $modelsSoal[0],
    'formId' => 'dynamic-form',
    'formFields' => [
        'sesi_id',
        'bobot_soal',
        'kode_soal',
        'teks_soal',
        'skor_maks',
        'nama_file',
        'data'
    ],
]); ?>

<div class="container-items"><!-- widgetContainer -->
    <?php foreach ($modelsSoal as $i => $modelSoal): ?>
        <div class="item panel panel-default"><!-- widgetBody -->
            <div class="panel-heading">
                <h3 class="panel-title pull-left">Soal <span class="soal-number"></span></h3>
                <div class="pull-right">
                    <button type="button" class="add-item btn btn-success btn-sm">Tambah</button>
                    <button type="button" class="remove-item btn btn-danger btn-sm">Hapus</button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <?= $form->field($modelSoal, "[{$i}]sesi_id")->dropDownList($jadwal, ['prompt' => 'Pilih Jadwal']) ?>
                <?= $form->field($modelSoal, "[{$i}]bobot_soal")->textInput() ?>
                <?= $form->field($modelSoal, "[{$i}]kode_soal")->textInput(['maxlength' => true]) ?>
                <?= $form->field($modelSoal, "[{$i}]teks_soal")->textarea(['rows' => 6]) ?>
                <?= $form->field($modelSoal, "[{$i}]skor_maks")->textInput() ?>
                <?= $form->field($modelSoal, "[{$i}]nama_file")->textInput(['maxlength' => true]) ?>
                <?= $form->field($modelSoal, "[{$i}]data")->textInput() ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php DynamicFormWidget::end(); ?>

<div class="form-group">
    <?= Html::submitButton('Simpan Semua', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>


<?php
$js = <<<JS
function updateSoalNumbers() {
    $('.dynamicform_wrapper .item').each(function(index) {
        $(this).find('.soal-number').text(index + 1);
    });
}
// Jalankan pertama kali
updateSoalNumbers();

// Saat tombol tambah ditekan
$('.dynamicform_wrapper').on('afterInsert', function(e, item) {
    updateSoalNumbers();
});

// Saat tombol hapus ditekan
$('.dynamicform_wrapper').on('afterDelete', function(e) {
    updateSoalNumbers();
});
JS;

$this->registerJs($js);
?>