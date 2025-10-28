<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Penilaian Akhir Mahasiswa';
?>

<div class="penilaian-akhir-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nim',
            [
                'attribute' => 'nilai_sikap',
                'label' => 'Nilai Sikap (Manual)',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::input(
                        'number',
                        "nilai_sikap[{$model->id}]",
                        $model->nilai_sikap ?? '',
                        [
                            'class' => 'form-control text-center nilai-manual-input nilai-sikap-input',
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                            'style' => 'width:80px; margin:auto;',
                            'data-id' => $model->id,
                            'data-field' => 'nilai_sikap', // Tambahkan data-field
                        ]
                    );
                },
            ],
            [
                'attribute' => 'nilai_kedisiplinan',
                'label' => 'Nilai Kedisiplinan (Manual)',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::input(
                        'number',
                        "nilai_kedisiplinan[{$model->id}]",
                        $model->nilai_kedisiplinan ?? '',
                        [
                            'class' => 'form-control text-center nilai-manual-input nilai-kedisiplinan-input',
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                            'style' => 'width:80px; margin:auto;',
                            'data-id' => $model->id,
                            'data-field' => 'nilai_kedisiplinan', // Tambahkan data-field
                        ]
                    );
                },
            ],
            // Di dalam GridView columns:
            [
                'label' => 'Skor',
                'value' => function ($model) {
                    // $model sekarang memiliki properti 'avg_skor' dari query di atas
                    return round($model->avg_skor, 2) ?? 0;
                },
            ],
            [
                'attribute' => 'nilai_akhir',
                'label' => 'Nilai Akhir',
                'format' => 'raw',
                'value' => function ($model) {
                    $nilai_akhir = $model->nilai_akhir ?? 0;
                    // Beri ID unik agar bisa diupdate JS
                    return Html::tag('span', round($nilai_akhir, 2), [
                        'id' => "nilai-akhir-{$model->id}",
                        'class' => 'nilai-akhir-display',
                        'style' => 'font-weight: bold;'
                    ]);
                }
            ],
        ],
    ]) ?>
</div>

<?php
// PERUBAHAN DI SINI: Arahkan URL ke PengerjaanController
$updateUrl = Url::to(['pengerjaan/update-nilai-manual']);
// Perbaikan CSRF Token dari sebelumnya
$csrfToken = Yii::$app->request->getCsrfToken();

$script = <<<JS

// Fungsi untuk menghitung dan mengupdate nilai akhir di UI
function calculateHybrid(row) {
    const sikap = parseFloat(row.find('.nilai-sikap-input').val()) || 0;
    const disiplin = parseFloat(row.find('.nilai-kedisiplinan-input').val()) || 0;
    const skor_ai = parseFloat(row.find('.skor-ai-val').text()) || 0;

    // 1. Hitung Skor Manual (Rata-rata)
    const skor_manual = (sikap + disiplin) / 2;

    // 2. Hitung Nilai Akhir Hibrida
    // (Skor Manual * 70%) + (Skor AI * 30%)
    const nilai_akhir = (skor_manual * 0.7) + (skor_ai * 0.3);

    // 3. Update tampilan Nilai Akhir di baris yang sama
    row.find('.nilai-akhir-display').text(nilai_akhir.toFixed(2));
}

// Handler ketika salah satu nilai manual (sikap/disiplin) diubah
$(document).on('change', '.nilai-manual-input', function() {
    const id = $(this).data('id');
    const field = $(this).data('field');
    const value = $(this).val();
    const \$row = $(this).closest('tr'); // Ambil baris (tr)
    
    // 1. Hitung dan update UI secara instan
    calculateHybrid(\$row);

    // 2. Kirim data ke server untuk disimpan
    $.ajax({
        url: '{$updateUrl}', // URL ini sekarang sudah benar
        type: 'POST',
        data: {
            id: id,
            field: field,
            value: value,
            _csrf: '{$csrfToken}'
        },
        success: function(res) {
            if(res.status === 'success') {
                console.log('Tersimpan: ' + field + ' = ' + value);
                // Update lagi nilai akhir dari server (untuk memastikan konsistensi)
                $('#nilai-akhir-' + id).text(res.nilai_akhir);
            } else {
                console.error('Gagal menyimpan:', res.message);
                alert('Gagal menyimpan nilai. Cek console.');
            }
        },
        error: function() {
            console.error('AJAX Error');
            alert('Gagal menghubungi server.');
        }
    });
});
JS;

$this->registerJs($script);
?>