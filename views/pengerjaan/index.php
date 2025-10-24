<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Halaman Utama';

// Definisikan kelas tombol di sini agar mudah diubah dan konsisten
$buttonClass = 'inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition duration-200 no-underline w-full md:w-auto text-center';

?>

<div class="container" style="margin-top: 100px;">
    <div class="flex flex-col  justify-between items-start md:items-center border-b border-gray-200 pb-6">

       

        <div class="flex flex-col md:flex-row w-full md:w-auto space-y-3 md:space-y-0 md:space-x-3 mt-4 md:mt-0">
            <?= Html::a(
                'Penilaian Akhir',
                Url::to(['pengerjaan/penilaian-akhir']),
                ['class' => $buttonClass] // Gunakan variabel class
            ) ?>

            <?= Html::a(
                'Penilaian Soal',
                Url::to(['pengerjaan/penilaian-soal']),
                ['class' => $buttonClass] // Gunakan variabel class
            ) ?>
        </div>
    </div>
</div>