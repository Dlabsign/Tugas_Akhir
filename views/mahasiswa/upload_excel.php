<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Jadwal;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
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

<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">Import File Excel</h3>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <div class="row">
            <div class="alert alert-light col-md-4">
                <strong>Petunjuk:</strong>
                <ul>
                    <li>Kolom A = NIM, Kolom B = Semester</li>
                    <li>Data mulai dibaca dari baris ke-2</li>
                </ul>
            </div>
            <div class="col">
                <div class="row-md-6">
                    <?= $form->field($uploadModel, 'sesi_id')->dropDownList($jadwal, ['prompt' => 'Pilih Jadwal']) ?>
                </div>
                <div class="row-md-6">
                    <?= $form->field($uploadModel, 'excelFile')->fileInput() ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Upload Excel', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>