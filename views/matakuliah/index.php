<?php

use app\models\Matakuliah;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\Modal;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MatakuliahSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Mata Kuliah';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="matakuliah-index">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-200 pb-6">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 tracking-tight">
            <?= Html::encode($this->title) ?>
        </h1>

        <!-- Tombol buka modal create -->
        <?= Html::button('Tambah Mata Kuliah', [
            'class' => 'mt-4 md:mt-0 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition duration-200',
            'id' => 'modalButton'
        ]) ?>
    </div>

    <div class="card shadow-sm p-3 mt-4">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-striped table-hover align-middle'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'nama',
                'semester',
                [
                    'class' => ActionColumn::className(),
                    'contentOptions' => ['style' => 'width: 220px; text-align: center;'],
                    'template' => '{update} {delete} {restore}',
                    'urlCreator' => function ($action, $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    },
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a(
                                '<i class="bi bi-pencil-fill"></i> Edit',
                                'javascript:void(0);',
                                [
                                    'class' => 'btn btn-sm btn-info me-1 modalUpdateBtn',
                                    'data-url' => $url,
                                    'title' => 'Ubah Data',
                                ]
                            );
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a(
                                '<i class="bi bi-trash-fill"></i> Hapus',
                                $url,
                                [
                                    'class' => 'btn btn-sm btn-danger me-1',
                                    'data-confirm' => 'Apakah Anda yakin ingin menghapus data ini?',
                                    'data-method' => 'post',
                                    'title' => 'Hapus Data',
                                ]
                            );
                        },
                        'restore' => function ($url, $model, $key) {
                            if (isset($model->flag) && $model->flag == 0) {
                                return Html::a(
                                    '<i class="bi bi-arrow-counterclockwise"></i> Restore',
                                    $url,
                                    [
                                        'class' => 'btn btn-sm btn-success',
                                        'data-confirm' => 'Yakin ingin mengembalikan data?',
                                        'title' => 'Pulihkan Data',
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
// Modal tunggal untuk Create & Update
Modal::begin([
    'title' => '<h5 class="modal-title"></h5>',
    'id' => 'modalCreate',
    'options' => ['tabindex' => false],
]);
echo "<div id='modalContent'></div>";
Modal::end();

// URL dasar
$createUrl = Url::to(['matakuliah/create']);
$createUrlJs = json_encode($createUrl);

$script = <<<JS
// CREATE
$('#modalButton').on('click', function () {
    $('#modalCreate .modal-title').text('Buat Mata Kuliah Baru');
    $('#modalCreate').modal('show')
        .find('#modalContent')
        .load({$createUrlJs});
});

// UPDATE
$(document).on('click', '.modalUpdateBtn', function (e) {
    e.preventDefault();
    var url = $(this).data('url');
    $('#modalCreate .modal-title').text('Ubah Mata Kuliah');
    $('#modalCreate').modal('show')
        .find('#modalContent')
        .load(url);
});

// HANDLE SUBMIT FORM
$(document).on('beforeSubmit', 'form#matakuliah-form', function (e) {
    e.preventDefault();

    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function (response) {
            if (response.success) {
                // Tutup modal langsung tanpa notifikasi
                $('#modalCreate').modal('hide');
                // Refresh GridView
                $.pjax.reload({container: '#w0-pjax'});
            } else {
                // Jika validasi gagal, tampilkan ulang form dengan error
                $('#modalContent').html(response);
            }
        },
        error: function () {
            console.error('Gagal menyimpan data.');
        }
    });

    return false;
});
JS;

$this->registerJs($script);



?>