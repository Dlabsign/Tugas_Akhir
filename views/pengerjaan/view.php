<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Pengerjaan $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pengerjaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pengerjaan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'soal_id',
            'kode_soal',
            'mahasiswa_id',
            'waktu_pengumpulan',
            'jawaban_teks:ntext',
            'skor',
            'umpan_balik:ntext',
            'staff_check',
            'flag',
        ],
    ]) ?>

</div>
