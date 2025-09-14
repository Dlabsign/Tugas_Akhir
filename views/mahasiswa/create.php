<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Mahasiswa $model */
/** @var app\models\UploadExcelForm $uploadModel */

$this->title = 'Tambah Mahasiswa';
$this->params['breadcrumbs'][] = ['label' => 'Mahasiswa', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mahasiswa-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'uploadModel' => $uploadModel, // Kirimkan model upload ke _form
    ]) ?>

</div>