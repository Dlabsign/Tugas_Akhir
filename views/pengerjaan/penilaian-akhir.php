<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var array $grouped */

$this->title = 'Penilaian Akhir Mahasiswa';
?>

<div class="penilaian-akhir-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <h2>Nilai Manual</h2>
    <?php
    $mahasiswaQuery = \app\models\Mahasiswa::find();
    $mahasiswaDataProvider = new \yii\data\ActiveDataProvider(['query' => $mahasiswaQuery]);
    ?>
   

    <h2>Penilaian Per Soal</h2>

    <!-- Form Pencarian NIM -->
    <form method="get" class="mb-4">
        <div class="input-group">
            <input type="text" name="nim" class="form-control" placeholder="Cari NIM Mahasiswa" value="<?= Html::encode($nimSearch ?? '') ?>">
            <button class="btn btn-outline-primary" type="submit">Cari</button>
        </div>
    </form>

    <!-- Collapsible untuk Kode Soal menggunakan details/summary -->
    <?php foreach ($grouped as $kode => $group): ?>
        <details class="mb-4">
            <summary class="cursor-pointer bg-light p-3 border rounded">
                <strong>Kode Soal: <?= Html::encode($kode) ?></strong>
            </summary>
            <div class="mt-3 p-3 border rounded bg-white">
                <?php foreach ($group['soal_list'] as $item): ?>
                    <div class="soal-section mb-4">
                        <h4>Soal: <?= Html::encode($item['soal']->teks_soal) ?></h4>

                        <?= GridView::widget([
                            'dataProvider' => $item['dataProvider'],
                            'tableOptions' => ['class' => 'table table-striped table-hover align-middle'],
                            'columns' => [
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
                                    'attribute' => 'skor',
                                    'label' => 'Skor',
                                    'format' => 'raw',
                                    'value' => function ($model) {
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
                                [
                                    'label' => 'Nilai Akhir',
                                    'value' => function ($model) {
                                        return round($model->mahasiswa->nilai_akhir ?? 0, 2);
                                    },
                                ],
                            ],
                        ]); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </details>
    <?php endforeach; ?>
</div>

<?php
// JS untuk update nilai manual
$updateUrl = Url::to(['pengerjaan/update-nilai-manual']);
$csrfToken = Yii::$app->request->getCsrfToken();

$script = <<<JS

// Fungsi untuk menghitung dan mengupdate nilai akhir di UI
function calculateHybrid(row) {
    const sikap = parseFloat(row.find('.nilai-sikap-input').val()) || 0;
    const disiplin = parseFloat(row.find('.nilai-kedisiplinan-input').val()) || 0;
    const skor_ai = 0; // Tidak ada skor AI per soal di sini, tapi untuk konsistensi

    // 1. Hitung Skor Manual (Rata-rata)
    const skor_manual = (sikap + disiplin) / 2;

    // 2. Hitung Nilai Akhir Hibrida (tanpa skor AI untuk sekarang)
    const nilai_akhir = skor_manual; // Atau sesuaikan

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

// JS untuk update skor dari detail-jawaban
$updateSkorUrl = Url::to(['pengerjaan/update-skor']);
$skorScript = <<<JS
$(document).on('change', '.skor-input', function() {
    const id = $(this).data('id');
    const value = $(this).val();

    $.ajax({
        url: '$updateSkorUrl',
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

$this->registerJs($skorScript);
?>

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

    // Hitung rata-rata skor per soal sebagai skor_ai
    let totalSkor = 0;
    let count = 0;
    row.find('.skor-soal').each(function() {
        const skor = parseFloat($(this).text()) || 0;
        totalSkor += skor;
        count++;
    });
    const skor_ai = count > 0 ? totalSkor / count : 0;

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