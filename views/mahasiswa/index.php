    <?php

    use app\models\Mahasiswa;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\bootstrap5\Modal;
    use yii\helpers\ArrayHelper;

    /** @var yii\web\View $this */
    /** @var app\models\MahasiswaSearch $searchModel */
    /** @var yii\data\ActiveDataProvider $dataProvider */

    $this->title = 'Mahasiswa';
    $this->params['breadcrumbs'][] = $this->title;
    ?>
    <div class="mahasiswa-index">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-200 pb-6">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 tracking-tight">
                Daftar <?= Html::encode($this->title) ?></h1>
            </h1>
            <div class="flex ">
                <?= Html::a(
                    'Tambah Manual',
                    ['create-manual'],
                    [
                        'class' => 'mt-4 mr-2 md:mt-0 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition duration-200 no-underline',
                        'id' => 'modalManualBtn'
                    ]
                ) ?>

                <?= Html::a(
                    'Import Excel <span class="bi bi-file-earmark-spreadsheet"></span>',
                    ['create-excel'],
                    [
                        'class' => 'mt-4 md:mt-0 inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition duration-200 no-underline',
                        'id' => 'modalExcelBtn'
                    ]
                ) ?>

            </div>
        </div>

        <div class="space-y-8">
            <?php
            // Ambil semua kode_soal unik dari hasil filter (bukan seluruh data mentah)
            $kodeSoalList = \app\models\Mahasiswa::find()
                ->select('kode_soal')
                ->where(['flag' => 1])
                ->distinct()
                ->orderBy(['kode_soal' => SORT_ASC])
                ->all();

            // Loop tiap grup kode soal
            foreach ($kodeSoalList as $item):
                $kodeSoal = $item->kode_soal;

                // Kloning dataProvider & searchModel agar tetap bisa pakai filter Yii
                $query = clone $searchModel->search(Yii::$app->request->queryParams)->query;
                $query->andWhere(['kode_soal' => $kodeSoal]);

                $dataProviderGroup = new yii\data\ActiveDataProvider([
                    'query' => $query,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]);
            ?>
                <div class="card shadow-sm p-3 mb-5">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <?= Html::encode("Kode Soal: {$kodeSoal}") ?>
                    </h3>

                    <?= GridView::widget([
                        'dataProvider' => $dataProviderGroup,
                        'filterModel' => $searchModel,
                        'tableOptions' => ['class' => 'table table-striped table-hover'],
                        'options' => ['class' => 'shadow-sm p-0'],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'nim',
                            [
                                'attribute' => 'semester',
                                'label' => 'Semester',
                                'value' => function ($model) {
                                    return $model->semester ?? '-';
                                },
                                'filter' => ArrayHelper::map(
                                    \app\models\Mahasiswa::find()->select('semester')->distinct()->all(),
                                    'semester',
                                    'semester'
                                ),
                            ],
                            [
                                'attribute' => 'kode_soal',
                                'label' => 'Kode Sesi',
                                'value' => function ($model) {
                                    return $model->kode_soal ?? '-';
                                },
                                'filter' => ArrayHelper::map(
                                    \app\models\Mahasiswa::find()->select('kode_soal')->distinct()->all(),
                                    'kode_soal',
                                    'kode_soal'
                                ),
                            ],
                            [
                                'class' => \yii\grid\ActionColumn::class,
                                'contentOptions' => ['style' => 'width: 180px; text-align: center; white-space: nowrap;'],
                                'template' => '{update} {delete} {restore}',
                                'urlCreator' => function ($action, $model) {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                },
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="bi bi-pencil-fill"></span> Edit',
                                            '#',
                                            [
                                                'class' => 'btn btn-sm btn-info me-1 modalUpdateBtn',
                                                'title' => 'Ubah Data',
                                                'data-url' => $url, // ini penting untuk Ajax load
                                            ]
                                        );
                                    },
                                    'delete' => fn($url, $model) =>
                                    Html::a('<span class="bi bi-trash-fill"></span> Delete', $url, [
                                        'class' => 'btn btn-sm btn-danger me-1',
                                        'data-method' => 'post',
                                        'data-confirm' => 'Apakah Anda yakin?',
                                    ]),
                                    'restore' => fn($url, $model) =>
                                    isset($model->flag) && $model->flag == 0
                                        ? Html::a('<span class="bi bi-arrow-counterclockwise"></span> Restore', $url, [
                                            'class' => 'btn btn-sm btn-success',
                                            'data-confirm' => 'Yakin ingin mengembalikan?',
                                        ])
                                        : '',
                                ],
                            ],
                        ],
                    ]); ?>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
    </div>

    <?php
    Modal::begin([
        'title' => '<h5></h5>',
        'id' => 'modalCreate',
        'options' => ['tabindex' => false],
    ]);
    echo "<div id='modalContent'></div>";
    Modal::end();

    $manualUrl = Url::to(['create-manual']);
    $excelUrl = Url::to(['create-excel']);
    $manualUrlJs = json_encode($manualUrl);
    $excelUrlJs = json_encode($excelUrl);

    $script = <<<JS
// Tambah Manual
$('#modalManualBtn').on('click', function (e) {
    e.preventDefault();
    $('#modalCreate .modal-title').text('Tambah Manual');
    $('#modalCreate').modal('show')
        .find('#modalContent')
        // .load({$manualUrlJs});
         .load($(this).attr('href'));
});

// Import Excel
$('#modalExcelBtn').on('click', function (e) {
    e.preventDefault();
    $('#modalCreate .modal-title').text('Import Excel');
    $('#modalCreate').modal('show')
        .find('#modalContent')
        .load({$excelUrlJs});
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
    ?>