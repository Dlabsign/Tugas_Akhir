<?php

use app\models\Pengerjaan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PengerjaanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Penilaian';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengerjaan-index">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-200 pb-6">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 tracking-tight">
            <?= Html::encode($this->title) ?></h1>
        </h1>
    </div>

    <div class="card shadow-sm p-3">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-striped table-hover'],
            'options' => ['class' => ' shadow-sm p-0'], // Jika ingin tampilan dalam card
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                // 'id',
                // 'soal_id',
                'kode_soal',
                [
                    'label' => 'NIM Mahasiswa',
                    'attribute' => 'mahasiswa_id',
                    'value' => function ($model) {
                        return $model->mahasiswa ? $model->mahasiswa->nim : '-';
                    },

                ],
                // 'waktu_pengumpulan',
                'jawaban_teks:ntext',

                //'skor',
                'umpan_balik:ntext',
                //'staff_check',
                //'flag',
                [
                    'class' => ActionColumn::className(),
                    'contentOptions' => ['style' => 'width: 180px; text-align: center; white-space: nowrap;'],
                    'urlCreator' => function ($action, $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    },
                    'template' => '{update} {nilai} {delete} {restore}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="bi bi-pencil-fill"></span> Edit',
                                'javascript:void(0);',
                                [
                                    'title' => 'Ubah',
                                    'class' => 'btn btn-sm btn-info me-1 modalUpdateBtn',
                                    'data-url' => $url,
                                ]
                            );
                        },
                        'delete' => function ($url, $model, $key) {
                            // Ikon sampah
                            return Html::a('<span class="bi bi-trash-fill"></span> Delete', $url, [
                                'title' => 'Hapus',
                                'data-confirm' => 'Apakah Anda yakin?',
                                'data-method' => 'post',
                                'class' => 'btn btn-sm btn-danger me-1', // Tombol merah
                            ]);
                        },
                        'nilai' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="bi bi-check-circle-fill"></span> Nilai',
                                ['pengerjaan/nilai', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-sm btn-warning me-1',
                                    'title' => 'Nilai dengan Gemini'
                                ]
                            );
                        },
                        'restore' => function ($url, $model, $key) {
                            // Hanya tampil jika soft-deleted (asumsi flag = 0)
                            if (isset($model->flag) && $model->flag == 0) {
                                return Html::a('<span class="bi bi-arrow-counterclockwise"></span> Restore', $url, [
                                    'title' => 'Restore',
                                    'data-confirm' => 'Yakin ingin mengembalikan?',
                                    'class' => 'btn btn-sm btn-success', // Tombol hijau
                                ]);
                            }
                            return '';
                        },
                    ]
                ],
            ],
        ]); ?>
    </div>
</div>