<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\Detail_soal $soal */
$this->title = "Detail Soal";

?>
<h3>Detail Soal (Kode: <?= Html::encode($soal->kode_soal) ?>)</h3>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'teks_soal:ntext',
        // 'bobot_soal',
        // 'skor_maks',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{jawaban}',
            'buttons' => [
                'jawaban' => function ($url, $model) {
                    return Html::a(
                        '<span class="bi bi-eye"></span> Lihat Jawaban',
                        ['pengerjaan/detail-jawaban', 'id' => $model->id],
                        ['class' => 'btn btn-sm btn-primary']
                    );
                }
            ]
        ]
    ],
]); ?>