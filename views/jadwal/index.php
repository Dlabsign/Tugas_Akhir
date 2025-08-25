<?php

use app\models\Jadwal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\JadwalSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Jadwal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Jadwal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Mata Kuliah',
                'attribute' => 'matakuliah_id',
                'value' => function ($model) {
                    return $model->matakuliah ? $model->matakuliah->nama : '-';
                },
                'filter' => \yii\helpers\ArrayHelper::map(
                    \app\models\Matakuliah::find()->where(['flag' => 1])->all(),
                    'id',
                    'nama'
                ),
            ],
            [
                'label' => 'Jumlah Mahasiswa',
                'attribute' => 'jumlah_peserta',
            ],

            [
                'label' => 'Laboratorium',
                'attribute' => 'lab',
                'value' => function ($model) {
                    return $model->laboratorium ? $model->laboratorium->nama : '-';
                },
                'filter' => \yii\helpers\ArrayHelper::map(
                    \app\models\Laboratorium::find()->where(['flag' => 1])->all(),
                    'id',
                    'nama'
                ),
            ],
            'tanggal_jadwal',
            'waktu_mulai',
            'waktu_selesai',
            [
                'label' => 'Dibuat Oleh',
                'attribute' => 'dibuat_oleh_staff_id',
                'value' => function ($model) {
                    return $model->pengguna ? $model->pengguna->username : '-';
                },
                'filter' => \yii\helpers\ArrayHelper::map(
                    \app\models\Pengguna::find()->where(['flag' => 1])->all(),
                    'id',
                    'username'
                ),
            ],
            //'flag',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Jadwal $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>