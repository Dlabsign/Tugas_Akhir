    <?php

    use app\models\Mahasiswa;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
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
                        'class' =>
                        'mt-4 mr-2 md:mt-0 inline-block inline bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition duration-200 no-underline'
                    ]
                ) ?>
                <?= Html::a(
                    'Import Excel <span class="bi bi-file-earmark-spreadsheet"></span>',
                    ['create-excel'],
                    [
                        'class' =>
                        'mt-4 md:mt-0 inline-block inline bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition duration-200 no-underline'
                    ]
                ) ?>
            </div>
        </div>



        <div class="card shadow-sm p-3">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-striped table-hover'],
                'options' => ['class' => ' shadow-sm p-0'], // Jika ingin tampilan dalam card
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
                        'label' => 'Sesi',
                        'attribute' => 'sesi_id',
                        'value' => function ($model) {
                            return $model->sesi ? $model->sesi->sesi : '-';
                        },

                    ],
                    [
                        'label' => 'Mata Kuliah',
                        'attribute' => 'sesi_id',
                        'value' => function ($model) {
                            return $model->matakuliah ? $model->matakuliah->nama : '-';
                        },

                    ],
                    'nilai',
                    [
                    'class' => ActionColumn::className(),
                    'contentOptions' => ['style' => 'width: 180px; text-align: center; white-space: nowrap;'],
                    'urlCreator' => function ($action, $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    },
                    'template' => '{update} {delete} {restore}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            // Ikon pensil
                            return Html::a('<span class="bi bi-pencil-fill"></span> Edit', $url, [
                                'title' => 'Ubah',
                                'class' => 'btn btn-sm btn-info  me-1', // Tombol kuning
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
    </div>