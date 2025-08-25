<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pengguna $model */

$this->title = 'Update Pengguna: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pengguna', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pengguna-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
