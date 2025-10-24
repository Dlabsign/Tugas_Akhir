<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var array $soalList */
/** @var string $kode_soal */
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="p-8 bg-white shadow rounded-xl">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            Kode Soal: <span class="text-indigo-600"><?= Html::encode($kode_soal) ?></span>
        </h2>
    </div>

    <?php if (empty($soalList)): ?>
        <div class="text-gray-500 italic">Belum ada soal untuk kode ini.</div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse border border-gray-300 rounded-lg shadow-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-4 py-3 text-left">#</th>
                        <th class="border border-gray-300 px-4 py-3 text-left">Teks Soal</th>
                        <th class="border border-gray-300 px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($soalList as $index => $soal): ?>
                        <tr class="hover:bg-gray-50">
                            <!-- Urutan soal -->
                            <td class="border border-gray-300 px-4 py-2 text-gray-600 font-medium">
                                <?= $index + 1 ?>
                            </td>


                            <!-- Teks soal -->
                            <td class="border border-gray-300 px-4 py-2">
                                <?= Html::encode($soal['teks_soal']) ?>
                            </td>

                           

                         

                            <!-- Aksi -->
                            <td class="border border-gray-300 px-4 py-2  textgra space-x-2">
                                <?= Html::a(
                                    '✏️ Edit',
                                    ['update', 'id' => $soal['id']],
                                    ['class' => 'px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded shadow']
                                ) ?>
                                <?= Html::a(
                                    '🗑️ Delete',
                                    ['delete', 'id' => $soal['id']],
                                    [
                                        'class' => 'px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded shadow',
                                        'data' => [
                                            'confirm' => 'Apakah Anda yakin ingin menghapus soal ini?',
                                            'method' => 'post',
                                        ],
                                    ]
                                ) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>