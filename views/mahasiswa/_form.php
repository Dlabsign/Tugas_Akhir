<?php

use app\models\Jadwal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Mahasiswa $model */
/** @var app\models\UploadExcelForm $uploadModel */

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

<div class="mahasiswa-form">

    <!-- FORM 1: Input Manual -->
    <?php $form = ActiveForm::begin(); ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Input Manual</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'sesi_id')->dropDownList($jadwal, ['prompt' => 'Pilih Jadwal']) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'nim')->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'semester')->textInput() ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group" style="margin-top: 20px;">
        <?= Html::submitButton('Simpan Manual', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>


    <hr>

    <!-- FORM 2: Upload Excel -->
    <?php $form2 = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Import File Excel</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form2->field($uploadModel, 'sesi_id')->dropDownList($jadwal, ['prompt' => 'Pilih Jadwal']) ?>
                </div>
                <div class="col-md-6">
                    <?= $form2->field($uploadModel, 'excelFile')->fileInput() ?>
                </div>
            </div>
            <div class="alert alert-light">
                <strong>Petunjuk:</strong>
                <ul>
                    <li>Kolom A = NIM, Kolom B = Semester</li>
                    <li>Data mulai dibaca dari baris ke-2</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="form-group" style="margin-top: 20px;">
        <?= Html::submitButton('Upload Excel', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>