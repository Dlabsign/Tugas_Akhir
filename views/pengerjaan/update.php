<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pengerjaan $model */

$this->title = 'Update Pengerjaan: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pengerjaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pengerjaan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
