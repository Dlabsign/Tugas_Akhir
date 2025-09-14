<?php

use app\models\Detail_soal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\SoalSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Soals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="soal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Soal', ['create'], ['class' => 'btn btn-success']) ?>
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
                'label' => 'Sesi',
                'attribute' => 'sesi_id',
                'value' => function ($model) {
                    return $model->sesi->sesi ? $model->sesi->sesi : '-';
                },
                'filter' => \yii\helpers\ArrayHelper::map(
                    \app\models\Jadwal::find()->where(['flag' => 1])->all(),
                    'id',
                    'sesi'
                ),

            ],
            'bobot_soal',
            'kode_soal',
            'teks_soal:ntext',
            'skor_maks',
            //'flag',
            //'nama_file',
            //'data',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Detail_soal $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>