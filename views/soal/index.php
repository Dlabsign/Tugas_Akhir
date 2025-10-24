<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;

/** @var yii\web\View $this */
/** @var app\models\SoalSearch $searchModel */
/** @var ActiveDataProvider $dataProvider */
$this->title = 'Detail Soal';

$this->params['breadcrumbs'][] = $this->title;
$groupedSoal = [];
foreach ($dataProvider->getModels() as $soal) {
    if (!$soal->matakuliah || !$soal->sesi) {
        continue;
    }

    $matakuliahId = $soal->matakuliah_id;
    $sesiId = $soal->sesi_id;

    $waktuMulai   = substr($soal->sesi->waktu_mulai ?? '00:00', 0, 5);
    $waktuSelesai = substr($soal->sesi->waktu_selesai ?? '00:00', 0, 5);

    if (!isset($groupedSoal[$matakuliahId])) {
        $groupedSoal[$matakuliahId] = [
            'matakuliah_nama' => $soal->matakuliah->nama,
            'sessions' => [],
        ];
    }

    if (!isset($groupedSoal[$matakuliahId]['sessions'][$sesiId])) {
        $groupedSoal[$matakuliahId]['sessions'][$sesiId] = [
            'sesi_nama' => $soal->sesi->sesi,
            'waktu_display' => "{$waktuMulai} – {$waktuSelesai}",
            'soal_list' => [], // simpan semua soal di sesi ini
        ];
    }

    // tambahkan semua soal dalam sesi
    $groupedSoal[$matakuliahId]['sessions'][$sesiId]['soal_list'][] = [
        'soal_id'   => $soal->id,
        'kode_soal' => $soal->kode_soal,
    ];

    // tambahkan soal berdasarkan kode_soal
    $kode = $soal->kode_soal;
    if (!isset($groupedSoal[$matakuliahId]['sessions'][$sesiId]['soal_by_kode'][$kode])) {
        $groupedSoal[$matakuliahId]['sessions'][$sesiId]['soal_by_kode'][$kode] = [
            'kode_soal' => $kode,
            'soal_id'   => $soal->id, // ambil salah satu ID, hanya untuk link awal
        ];
    }
}
?>

<style>
    body {
        background-color: #fafafa;
    }
</style>
<div class="mahasiswa-index">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-200 pb-6">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 tracking-tight">
            Buat Soal Mata Kuliah
        </h1>
        <?= Html::a(
            'Buat Soal',
            ['create'],
            [
                'class' =>
                'mt-4 md:mt-0 inline-block inline bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition duration-200'
            ]
        ) ?>
    </div>

    <!-- List Soal -->
    <div class="space-y-6">
        <?php if (empty($groupedSoal)): ?>
            <div class="bg-white border border-gray-200 text-gray-600 p-6 rounded-xl shadow-sm text-center">
                Belum ada soal yang terdaftar. Silakan buat soal baru.
            </div>
        <?php else: ?>
            <?php foreach ($groupedSoal as $matakuliahId => $group): ?>
                <div class="bg-white border border-gray-200 p-6 rounded-xl shadow-sm hover:shadow-md transition">

                    <div class="flex justify-between items-end mb-4">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
                            <?= Html::encode($group['matakuliah_nama']) ?>
                        </h2>
                        <p class="text-sm font-medium text-gray-600">
                            Kode Soal:
                            <?php
                            $allKodeSoal = [];
                            foreach ($group['sessions'] as $session) {
                                foreach ($session['soal_list'] as $soal) {
                                    $allKodeSoal[] = Html::encode($soal['kode_soal']);
                                }
                            }
                            echo implode(' | ', array_unique($allKodeSoal));
                            ?>
                        </p>
                    </div>

                    <div class="grid" style="grid-auto-flow: column; grid-template-rows: repeat(2, auto); gap:0.5rem; align-items:stretch;">
                        <?php foreach ($group['sessions'] as $sesiId => $session): ?>
                            <?php foreach ($session['soal_by_kode'] as $kode => $soalKode): ?>
                                <?php
                                $buttonText = '(Sesi ' . Html::encode(str_replace('_', ' ', $session['sesi_nama'])) . ') | '
                                    . Html::encode($session['waktu_display'])
                                    . ' | Kode Soal: ' . Html::encode($soalKode['kode_soal']);

                                // link ke detail berdasarkan kode_soal
                                $link = Url::to(['view-by-kode', 'kode_soal' => $soalKode['kode_soal']]);
                                ?>
                                <?= Html::a(
                                    $buttonText,
                                    $link,
                                    [
                                        'class' => 'block w-full text-center bg-gray-200 hover:bg-gray-300 px-5 py-3 rounded-lg font-bold shadow-sm transition no-underline text-gray-700',
                                        'style' => 'text-decoration: none; color: black;',
                                    ]
                                ) ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>



                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>