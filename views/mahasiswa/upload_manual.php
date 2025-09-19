<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Jadwal;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Mahasiswa $model */

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

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Input Manual</h3>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>
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
        <div class="form-group">
            <?= Html::submitButton('Simpan Manual', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
