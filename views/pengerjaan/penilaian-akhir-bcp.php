<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\data\ActiveDataProvider $dataProvider */
?>

<div class="penilaian-akhir-index">
    <h1>Penilaian Akhir Mahasiswa</h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nim',
            [
                'attribute' => 'nilai_sikap',
                'label' => 'Nilai Sikap',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::input(
                        'number',
                        "nilai_sikap[{$model->id}]",
                        $model->nilai_sikap ?? '',
                        [
                            'class' => 'form-control text-center nilai-sikap-input',
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                            'style' => 'width:80px; margin:auto;',
                            'data-id' => $model->id,
                        ]
                    );
                },
            ],
            [
                'attribute' => 'nilai_kedisiplinan',
                'label' => 'Nilai Kedisiplinan',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::input(
                        'number',
                        "nilai_kedisiplinan[{$model->id}]",
                        $model->nilai_kedisiplinan ?? '',
                        [
                            'class' => 'form-control text-center nilai-kedisiplinan-input',
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                            'style' => 'width:80px; margin:auto;',
                            'data-id' => $model->id,
                        ]
                    );
                },
            ],
            [
                'label' => 'Skor',
                'value' => function ($model) {
                    return isset($model->pengerjaan) ? $model->pengerjaan->skor : '-';
                },
            ],
            'nilai_akhir',
        ],
    ]) ?>
</div>

<?php
$updateUrl = Url::to(['pengerjaan/update-nilai']);
$script = <<<JS
function updateNilai(id, field, value) {
    $.ajax({
        url: '{$updateUrl}',
        type: 'POST',
        data: {
            id: id,
            field: field,
            value: value,
            _csrf: yii.getCsrfToken()
        },
        success: function(res) {
            console.log('Tersimpan:', res);
        },
        error: function() {
            alert('Gagal menyimpan nilai.');
        }
    });
}

$(document).on('change', '.nilai-sikap-input', function() {
    const id = $(this).data('id');
    const value = $(this).val();
    updateNilai(id, 'nilai_sikap', value);
});

$(document).on('change', '.nilai-kedisiplinan-input', function() {
    const id = $(this).data('id');
    const value = $(this).val();
    updateNilai(id, 'nilai_kedisiplinan', value);
});
JS;

$this->registerJs($script);
?>