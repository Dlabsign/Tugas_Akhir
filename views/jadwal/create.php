<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Jadwal $model */

$this->title = 'Create Jadwal';
$this->params['breadcrumbs'][] = ['label' => 'Jadwals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
