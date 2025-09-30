<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Soal $modelUtama */
/** @var app\models\Detail_soal[] $modelsSoal */
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="soal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_updated_form', [
        'modelsSoal' => $modelsSoal,
        'modelUtama' => $modelUtama,
    ]) ?>
</div>