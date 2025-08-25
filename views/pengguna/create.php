<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pengguna $model */

$this->title = 'Create Pengguna';
$this->params['breadcrumbs'][] = ['label' => 'Pengguna', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengguna-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
