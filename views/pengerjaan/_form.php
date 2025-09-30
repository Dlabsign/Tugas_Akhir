<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Pengerjaan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengerjaan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'soal_id')->textInput() ?>

    <?= $form->field($model, 'kode_soal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mahasiswa_id')->textInput() ?>

    <?= $form->field($model, 'waktu_pengumpulan')->textInput() ?>

    <?= $form->field($model, 'jawaban_teks')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'skor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'umpan_balik')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'staff_check')->textInput() ?>

    <?= $form->field($model, 'flag')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
