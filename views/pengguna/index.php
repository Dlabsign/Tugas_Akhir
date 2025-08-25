<?php

use app\models\Pengguna;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PenggunaControllerSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pengguna';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengguna-index">

    <div class="row">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>
            <?= Html::a('Create Pengguna', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>


    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Role',
                'attribute' => 'type',
                'value' => function ($model) {
                    $roles = [
                        1 => 'Superadmin',
                        2 => 'Kepala Laboratorium',
                        3 => 'Asisten Laboratorium',
                    ];
                    return $roles[$model->type] ?? $model->type;
                },
            ],
            'username',
            'semester',
            [
                'label' => 'Laboratorium',
                'attribute' => 'lab',
                'value' => function ($model) {
                    return $model->laboratorium ? $model->laboratorium->nama : '-';
                },
                'filter' => \yii\helpers\ArrayHelper::map(
                    \app\models\Laboratorium::find()->where(['flag' => 1])->all(),
                    'id',
                    'nama'
                ),
            ],            //'dibuat_pada',
            //'diperbarui_pada',
            //'flag',
            'nim',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Pengguna $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'template' => '{view} {update} {delete} {restore}',
            ],

        ],
    ]); ?>


</div>