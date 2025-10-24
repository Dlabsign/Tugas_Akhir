<?php

use app\models\Detail_soal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Mahasiswa $model */

$kodesoal = ArrayHelper::map(
    Detail_soal::find()
        ->where(['detail_soal.flag' => 1])
        ->joinWith(['matakuliah'])
        ->all(),
    'kode_soal',
    function ($model) {
        return $model->kode_soal . ' - ' . ($model->matakuliah ? $model->matakuliah->nama : '(Mata Kuliah Tidak Ada)');
    }
);
?>

<div class="mahasiswa-form">
    <?php $form = ActiveForm::begin([
        'id' => 'formManual',
        'enableAjaxValidation' => false,
        'action' => ['create-manual'], // penting untuk submit ke controller yang benar
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'kode_soal')->dropDownList($kodesoal, ['prompt' => 'Pilih Kode Soal']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'nim')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'semester')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('Simpan Manual', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
