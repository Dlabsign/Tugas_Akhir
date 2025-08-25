<?php

use app\models\Laboratorium;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

$this->title = 'Laboratorium';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="laboratorium-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button('Buat Ruang Laboratorium', [
            'value' => Url::to(['laboratorium/create']),
            'class' => 'btn btn-success',
            'id' => 'modalButton'
        ]) ?>
    </p>
    <?php
    Modal::begin([
        'title' => '<h4>Create Laboratorium</h4>',
        'id' => 'modal',
        'size' => 'modal-lg',
        'options' => [
            'tabindex' => false // penting biar form input bisa fokus tanpa error aria-hidden
        ],
        'clientOptions' => [
            'backdrop' => 'static', // modal tidak tertutup kalau klik luar
            'keyboard' => true,     // bisa ditutup pakai ESC
        ],
    ]);

    echo "<div id='modalContent'></div>";

    Modal::end();
    ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nama',
            'ruang',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Laboratorium $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'template' => '{view} {update} {delete} {restore}',
            ],
        ],
    ]); ?>
</div>
<?php
$this->registerJs("
    $('#modalButton').click(function(){
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });
");
?>