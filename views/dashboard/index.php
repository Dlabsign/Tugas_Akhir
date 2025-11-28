<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Dashboard Superadmin';

$user = Yii::$app->user->identity;
$role = match ($user->type) {
    1 => 'Superadmin',
    2 => 'Kalab',
    3 => 'Asisten',
    default => 'User',
};
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="min-h-screen  p-2 md:p-10">
    <!-- Bagian Header Selamat Datang -->
    <div class="w-full mb-4">
        <h2 class="text-4xl font-bold text-indigo-700">
            <?= Html::encode(strtoupper($user->username)) ?>
            <span class="text-3xl font-normal text-gray-600">(<?= Html::encode($role) ?>)</span>
        </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 w-full">
        <div class="group bg-white border border-gray-200 rounded-lg shadow-lg p-6 flex flex-col justify-between transition duration-300 ease-in-out hover:-translate-y-1 hover:shadow-xl no-underline decoration-none hover:no-underline">
            <div>
                <div class="text-indigo-400 flex items-center justify-between mb-4">
                    <h1 class="font-semibold">Mata Kuliah</h1>
                    <i class="bi bi-calendar-event-fill text-3xl "></i>
                </div>
                <p class="text-5xl font-bold text-gray-900 mt-2"><?= $jumlahMahasiswa ?></p>
            </div>
            <div class="mt-4">
                <a href="<?= Url::to(['/mahasiswa/index']) ?>" style="text-decoration: none;">
                    <p class="text-sm font-semibold text-indigo-600  group-hover:underline">
                        Lihat Detail <i class="bi bi-arrow-right-short"></i>
                    </p>
                </a>
            </div>
        </div>

        <div class="group bg-white border border-gray-200 rounded-lg shadow-lg p-6 flex flex-col justify-between transition duration-300 ease-in-out hover:-translate-y-1 hover:shadow-xl decoration-none hover:no-underline">
            <div>
                <div class="text-blue-400 flex items-center justify-between mb-4">
                    <h1 class=" font-semibold ">Sesi</h1>
                    <i class="bi bi-calendar-event-fill text-3xl "></i>
                </div>
                <p class="text-5xl font-bold text-gray-900 mt-2"><?= $jumlahSesi ?></p>
            </div>
            <div class="mt-4">
                <a href="<?= Url::to(['/jadwal/index']) ?>" style="text-decoration: none;">
                    <p class="text-sm font-semibold text-blue-600 group-hover:underline">
                        Lihat Detail <i class="bi bi-arrow-right-short"></i>
                    </p>
                </a>
            </div>
        </div>

        <div class="group bg-white border border-gray-200 rounded-lg shadow-lg p-6 flex flex-col justify-between transition duration-300 ease-in-out hover:-translate-y-1 hover:shadow-xl decoration-none hover:no-underline">
            <div>
                <div class="text-blue-400 flex items-center justify-between mb-4">
                    <h1 class=" font-semibold ">Mata Kuliah</h1>
                    <i class="bi bi-calendar-event-fill text-3xl "></i>
                </div>
                <p class="text-5xl font-bold text-gray-900 mt-2"><?= $jumlahMatakuliah ?></p>
            </div>
            <div class="mt-4">
                <a href="<?= Url::to(['/matakuliah/index']) ?>" style="text-decoration: none;">
                    <p class="text-sm font-semibold text-green-600 group-hover:underline">
                        Lihat Detail <i class="bi bi-arrow-right-short"></i>
                    </p>
                </a>
            </div>
        </div>
    </div>



    <div class="w-full bg-white rounded-lg shadow-lg p-6 mt-4">
        <?php if (!empty($sesiBerlangsung)) : ?>
            <div class="flex flex-row items-center mb-2 border-b border-gray-200 pb-2">
                <span class="relative flex h-3 w-3 mr-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
                <h2 class="text-2xl font-bold text-gray-800">Sesi Berlangsung</h2>
            </div>
            <div class="divide-y divide-gray-200">
                <?php foreach ($sesiBerlangsung as $sesi) : ?>
                    <div class="p-2 ">
                        <h4 class="font-semibold text-lg text-red-700">
                            <?= Html::encode($sesi->matakuliah->nama ?? '-') ?>
                        </h4>
                        <p class="text-sm text-gray-600 mt-1">
                            Sesi: <?= Html::encode($sesi->sesi ?? '-') ?>
                            <span class="text-gray-400 mx-2">|</span>
                            Tanggal: <?= Yii::$app->formatter->asDate($sesi->tanggal_jadwal, 'php:d M Y') ?>
                            <span class="text-gray-400 mx-2">|</span>
                            Jam: <?= Html::encode($sesi->waktu_mulai) ?> - <?= Html::encode($sesi->waktu_selesai) ?>
                            <span class="text-gray-400 mx-2">|</span>
                            Ruang: <?= Html::encode($sesi->laboratorium->ruang ?? '-') ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="flex flex-row items-center mb-2 border-b border-gray-200 pb-2">

                <h4 class="font-bold text-gray-500">Tidak ada Sesi yang Berlangsung</h4>
            </div>
            <!-- <p class="text-gray-500">Tidak ada sesi yang berlangsung saat ini.</p> -->
        <?php endif; ?>

    </div>


</div>