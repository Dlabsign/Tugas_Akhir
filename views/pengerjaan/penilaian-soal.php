<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;


$this->title = "Penilaian";
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mahasiswa-index">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-200 pb-6">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 tracking-tight">
            Pengerjaan Soal
        </h1>
    </div>
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
                                $buttonText = Html::encode($session['waktu_display'])
                                    . ' | Kode: ' . Html::encode($soalKode['kode_soal']);
                                $link = Url::to(['pengerjaan/detail-soal', 'kode_soal' => $soalKode['kode_soal']]);
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