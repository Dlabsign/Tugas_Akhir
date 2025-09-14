<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Soal $model */

$this->title = 'Create Soal';
$this->params['breadcrumbs'][] = ['label' => 'Soals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="soal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'modelsSoal' => $modelsSoal,
    ]) ?>

</div>
