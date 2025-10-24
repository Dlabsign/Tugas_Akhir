<?php

use app\models\Jadwal;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

$this->title = 'Jadwal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-200 pb-6">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 tracking-tight">
            List <?= Html::encode($this->title) ?>
        </h1>
        <?= Html::button('Buat Jadwal', [
            'class' => 'mt-4 md:mt-0 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition duration-200',
            'id' => 'modalButton'
        ]) ?>
    </div>

    <div class="card shadow-sm p-3">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-striped table-hover'],
            'options' => ['class' => 'shadow-sm p-0'],
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
                    'label' => 'Ruangan',
                    'attribute' => 'laboratorium_id',
                    'value' => function ($model) {
                        return $model->laboratorium ? $model->laboratorium->nama : '-';
                    },
                    'filter' => \yii\helpers\ArrayHelper::map(
                        \app\models\Laboratorium::find()->where(['flag' => 1])->all(),
                        'id',
                        'nama'
                    ),
                ],
                'sesi',
                'tanggal_jadwal',
                'waktu_mulai',
                'waktu_selesai',
                // [
                //     'label' => 'Dibuat Oleh',
                //     'attribute' => 'dibuat_oleh_staff_id',
                //     'value' => function ($model) {
                //         return $model->pengguna ? $model->pengguna->username : '-';
                //     },
                //     'filter' => \yii\helpers\ArrayHelper::map(
                //         \app\models\Pengguna::find()->where(['flag' => 1])->all(),
                //         'id',
                //         'username'
                //     ),
                // ],
                //'flag',
                [
                    'class' => ActionColumn::class,
                    'contentOptions' => [
                        'style' => 'width: 180px; text-align: center; white-space: nowrap;'
                    ],
                    'urlCreator' => function ($action, $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    },
                    'template' => '{update} {delete} {restore}',
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
                            return Html::a(
                                '<span class="bi bi-trash-fill"></span> Delete',
                                $url,
                                [
                                    'title' => 'Hapus',
                                    'data-confirm' => 'Apakah Anda yakin?',
                                    'data-method' => 'post',
                                    'class' => 'btn btn-sm btn-danger me-1',
                                ]
                            );
                        },
                        'restore' => function ($url, $model, $key) {
                            if (isset($model->flag) && $model->flag == 0) {
                                return Html::a(
                                    '<span class="bi bi-arrow-counterclockwise"></span> Restore',
                                    $url,
                                    [
                                        'title' => 'Restore',
                                        'data-confirm' => 'Yakin ingin mengembalikan?',
                                        'class' => 'btn btn-sm btn-success',
                                    ]
                                );
                            }
                            return '';
                        },
                    ],
                ],
            ],
        ]); ?>


    </div>
</div>

<?php
// Modal tunggal untuk create & update
Modal::begin([
    'title' => '<h5></h5>',
    'id' => 'modalCreate',
    'options' => ['tabindex' => false],
]);
echo "<div id='modalContent'></div>";
Modal::end();

// URL create
$createUrl = Url::to(['create']);
$createUrlJs = json_encode($createUrl);

$script = <<<JS
// CREATE
$('#modalButton').on('click', function () {
    $('#modalCreate .modal-title').text('Buat Ruangan Baru');
    $('#modalCreate').modal('show')
        .find('#modalContent')
        .load({$createUrlJs});
});

// UPDATE
$(document).on('click', '.modalUpdateBtn', function (e) {
    e.preventDefault();
    var url = $(this).data('url');
    $('#modalCreate .modal-title').text('Ubah Ruangan');
    $('#modalCreate').modal('show')
        .find('#modalContent')
        .load(url);
});
JS;

$this->registerJs($script);
