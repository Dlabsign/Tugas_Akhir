<?php

use app\models\Laboratorium;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Pengguna $model */
/** @var yii\widgets\ActiveForm $form */
?>
<div class="pengguna-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'username')->textInput(['maxlength' => true])->label('<b>Username</b>') ?>
        </div>

        <div class="col-md-6">
            <?php if ($model->isNewRecord): ?>
                <?= $form->field($model, 'password')->passwordInput(['maxlength' => true])->label('<b>Password</b>') ?>
            <?php else: ?>
                <?= $form->field($model, 'new_password')->passwordInput(['maxlength' => true])->label('<b>Password Baru</b>') ?>
                <?= $form->field($model, 'confirm_password')->passwordInput(['maxlength' => true])->label('<b>Konfirmasi Password</b>') ?>
            <?php endif; ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'type')->dropDownList(
                $model::getUserType(),
                ['prompt' => 'Pilih Type']
            )->label('<b>Type</b>') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'semester')->textInput()->label('<b>Semester</b> (Mahasiswa)') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'lab_id')->dropDownList(
                ArrayHelper::map(
                    Laboratorium::find()->where(['flag' => 1])->all(), // ambil hanya yang aktif
                    'id',   // value dropdown
                    'nama'  // label dropdown
                ),
                ['prompt' => '-- Pilih Laboratorium --'] // opsi default
            )->label('<b>Lab</b>') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'nim')->textInput()->label('<b>NIM</b> (Mahasiswa)') ?>
        </div>
    </div>

    <div class="form-group flex items-center gap-3">
        <?= Html::a('Kembali', ['index'], ['class' => 'inline-block bg-gray-700 hover:bg-gray-500 text-gray-200 font-semibold py-3 px-6 rounded-lg transition duration-150 underline underline-offset-1']) ?>
        <?= Html::submitButton('Simpan', ['class' => 'inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition duration-200 no-underline']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs("
    $('#toggle-password').on('click', function(){
        var input = $('#password-input');
        var icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('bi-eye-fill').addClass('bi-eye-slash-fill');
        } else {
            input.attr('type', 'password');
            icon.removeClass('bi-eye-slash-fill').addClass('bi-eye-fill');
        }
    });
");
?>