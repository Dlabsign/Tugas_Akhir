<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\Detail_soal $soal */
$this->title = "Detail Jawaban";

?>

<div class="card shadow-sm p-4 space-y-3">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Jawaban Mahasiswa untuk Soal:</h3>
        <?= Html::a(
            '<i class="bi bi-search"></i> Cek Duplikasi Jawaban',
            ['pengerjaan/cek-duplikat', 'id' => $soal->id],
            [
                'class' => 'btn btn-danger shadow-sm',
                'data-confirm' => 'Jalankan pengecekan duplikasi untuk semua jawaban?',
            ]
        ) ?>
    </div>

    <p><b><?= Html::encode($soal->teks_soal) ?></b></p>

    <!-- Flash hasil -->
    <!-- <?php if (Yii::$app->session->hasFlash('duplikatList') || Yii::$app->session->hasFlash('miripList')): ?>
        <div class="alert alert-info mt-3">
            <h5><i class="bi bi-exclamation-triangle"></i> Hasil Pengecekan Duplikasi</h5>

            <?php $duplikatList = Yii::$app->session->getFlash('duplikatList', []); ?>
            <?php $miripList = Yii::$app->session->getFlash('miripList', []); ?>

            <?php if ($duplikatList): ?>
                <h6 class="text-danger">🟥 Duplikat (Sama Persis):</h6>
                <ul>
                    <?php foreach ($duplikatList as $pair): ?>
                        <li><?= Html::encode($pair) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if ($miripList): ?>
                <h6 class="text-warning">🟨 Mirip (Hampir Sama):</h6>
                <ul>
                    <?php foreach ($miripList as $pair): ?>
                        <li><?= Html::encode($pair) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endif; ?> -->

    <!-- Tabel Jawaban -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-hover align-middle'],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'mahasiswa_id',
                'label' => 'NIM Mahasiswa',
                'value' => fn($model) => $model->mahasiswa->nim ?? '-',
            ],
            'jawaban_teks:ntext',
            [
                'attribute' => 'waktu_pengumpulan',
                'label' => 'Waktu Pengumpulan',
                'value' => function ($model) {
                    if (empty($model->waktu_pengumpulan)) {
                        return '-';
                    }
                    return $model->waktu_pengumpulan;

                    // Yii::$app->formatter->timeZone = 'UTC';
                    // return Yii::$app->formatter->asTime($model->waktu_pengumpulan, 'php:H:i:s')
                    //     . ' - ' .
                    //     Yii::$app->formatter->format($model->waktu_pengumpulan, 'date');
                },
            ],
            [
                'attribute' => 'umpan_balik',
                'label' => 'Umpan Balik',
                'format' => 'ntext',
                'value' => function ($model) {
                    $val = (string)($model->umpan_balik ?? '');
                    return trim($val) !== '' ? $val : '-';
                },
            ],
            [
                'attribute' => 'flag',
                'label' => 'Status',
                'format' => 'raw',
                'value' => function ($model) {
                    return match ($model->flag) {
                        1 => '<span class="badge bg-danger">Duplikat</span>',
                        2 => '<span class="badge bg-warning text-dark">Mirip</span>',
                        default => '<span class="badge bg-success">Unik</span>',
                    };
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{nilai}',
                'buttons' => [
                    'nilai' => fn($url, $model) =>
                    Html::a(
                        '<span class="bi bi-check-circle-fill"></span> Nilai',
                        ['pengerjaan/nilai', 'id' => $model->id],
                        ['class' => 'btn btn-sm btn-warning']
                    ),
                ],
            ],
            [
                'attribute' => 'skor',
                'label' => 'Skor',
                'format' => 'raw',
                'value' => function ($model) {
                    // Disable jika umpan_balik kosong atau null
                    $disabled = (empty(trim($model->umpan_balik))) ? 'disabled' : '';

                    return Html::input(
                        'number',
                        "skor[{$model->id}]",
                        $model->skor ?? '',
                        [
                            'class' => 'form-control text-center skor-input',
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                            'style' => 'width:80px; margin:auto;',
                            $disabled => true,
                            'data-id' => $model->id,
                        ]
                    );
                },
            ],
        ],
    ]); ?>
</div>


<?php
$updateUrl = \yii\helpers\Url::to(['pengerjaan/update-skor']);
$js = <<<JS
$(document).on('change', '.skor-input', function() {
    const id = $(this).data('id');
    const value = $(this).val();

    $.ajax({
        url: '$updateUrl',
        type: 'POST',
        data: {id: id, skor: value, _csrf: yii.getCsrfToken()},
        success: function(res) {
            if (res.success) {
                alert('Skor berhasil disimpan.');
            } else {
                alert('Gagal menyimpan skor: ' + res.message);
            }
        },
        error: function() {
            alert('Terjadi kesalahan jaringan.');
        }
    });
});
JS;
$this->registerJs($js);
?>
