<?php

use app\models\Pengguna;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;


/** @var yii\web\View $this */
/** @var app\models\PenggunaControllerSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
$this->title = 'Pengguna';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengguna-index">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-200 pb-6">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 tracking-tight">
            Buat <?= Html::encode($this->title) ?></h1>
        </h1>
        <?= Html::a(
            'Create Pengguna',
            ['create'],
            [
                'class' =>
                'mt-4 md:mt-0 inline-block inline bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition duration-200 no-underline'
            ]
        ) ?>
    </div>

    <div class="card shadow-sm p-3">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-striped table-hover'],
            'options' => ['class' => ' shadow-sm p-0'], // Jika ingin tampilan dalam card
            'columns' => [
                // 1. Kolom Serial (#)
                [
                    'class' => 'yii\grid\SerialColumn',
                    'contentOptions' => ['style' => 'width: 50px; text-align: center; font-weight: bold;'],
                ],
                // 2. Kolom Role (Peran)
                [
                    'label' => 'Role',
                    'attribute' => 'type',
                    'contentOptions' => ['style' => 'width: 150px; text-align: center;'],
                    'value' => function ($model) {
                        $roles = [
                            1 => ['label' => 'Superadmin', 'class' => 'primary'],
                            2 => ['label' => 'Kalab', 'class' => 'info'],
                            3 => ['label' => 'Aslab', 'class' => 'success'],
                        ];
                        $role = ArrayHelper::getValue($roles, $model->type, ['label' => 'Lainnya', 'class' => 'secondary']);

                        // 💡 Gunakan Badge Bootstrap (bg-class)
                        return Html::tag('span', $role['label'], [
                            'class' => 'badge bg-' . $role['class']
                        ]);
                    },
                    'format' => 'raw', // Penting untuk menampilkan HTML
                ],

                // 3. Kolom Username
                [
                    'attribute' => 'username',
                    'contentOptions' => ['style' => 'font-weight: 500;'], // Sedikit penekanan
                ],

                // 4. Kolom Semester - Dibuat seperti badge/pill untuk estetika
                [
                    'attribute' => 'semester',
                    'contentOptions' => ['style' => 'width: 100px; text-align: center;'],
                    'value' => function ($model) {
                        // 💡 Styling seperti badge
                        return Html::tag('span', $model->semester, [
                            'class' => 'badge rounded-pill bg-light text-dark border'
                        ]);
                    },
                    'format' => 'raw',
                ],

                // 5. Kolom NIM - Dibuat rata tengah
                [
                    'attribute' => 'nim',
                    'label' => 'NIM',
                    'contentOptions' => ['style' => 'text-align: center;'],
                ],

                // 6. Kolom Laboratorium
                [
                    'label' => 'Laboratorium',
                    'attribute' => 'lab_id',
                    'value' => function ($model) {
                        return $model->laboratorium ? $model->laboratorium->nama : Html::tag('span', 'Tidak Terkait', ['class' => 'badge bg-secondary']);
                    },
                    'filter' => ArrayHelper::map(
                        \app\models\Laboratorium::find()->where(['flag' => 1])->all(),
                        'id',
                        'nama'
                    ),
                    'filterInputOptions' => ['prompt' => 'Pilih Lab', 'class' => 'form-control form-control-sm'],
                    'format' => 'raw',
                ],

                // 7. Kolom Aksi - Tombol bergaya
                [
                    'class' => ActionColumn::className(),
                    'contentOptions' => ['style' => 'width: 180px; text-align: center; white-space: nowrap;'],
                    'urlCreator' => function ($action, $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    },
                    'template' => '{view} {update} {delete} {restore}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            // Ikon mata
                            return Html::a('<span class="bi bi-eye-fill"></span> View', $url, [
                                'title' => 'Lihat',
                                'class' => 'btn btn-sm btn-info me-1', // Tombol biru
                            ]);
                        },
                        'update' => function ($url, $model, $key) {
                            // Ikon pensil
                            return Html::a('<span class="bi bi-pencil-fill"></span> Edit', $url, [
                                'title' => 'Ubah',
                                'class' => 'btn btn-sm btn-warning text-white me-1', // Tombol kuning
                            ]);
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