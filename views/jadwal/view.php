<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Jadwal $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jadwals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="jadwal-view">

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
            'matakuliah_id',
            'jumlah_peserta',
            'laboratorium_id',
            'tanggal_jadwal',
            'waktu_mulai',
            'waktu_selesai',
            'dibuat_oleh_staff_id',
            'flag',
        ],
    ]) ?>

</div>
