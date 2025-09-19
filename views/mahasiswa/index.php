    <?php

    use app\models\Mahasiswa;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\helpers\ArrayHelper;

    /** @var yii\web\View $this */
    /** @var app\models\MahasiswaSearch $searchModel */
    /** @var yii\data\ActiveDataProvider $dataProvider */

    $this->title = 'Mahasiswa';
    $this->params['breadcrumbs'][] = $this->title;
    ?>
    <div class="mahasiswa-index">

        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('Tambah Manual', ['create-manual'], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Import Excel', ['create-excel'], ['class' => 'btn btn-success']) ?>
        </p>


        <?php // echo $this->render('_search', ['model' => $searchModel]); 
        ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'nim',
                [
                    'attribute' => 'semester',
                    'label' => 'Semester',
                    'value' => function ($model) {
                        return $model->semester ?? '-';
                    },
                    'filter' => ArrayHelper::map(
                        \app\models\Mahasiswa::find()->select('semester')->distinct()->all(),
                        'semester',
                        'semester'
                    ),
                ],
                [
                    'label' => 'Sesi',
                    'attribute' => 'sesi_id',
                    'value' => function ($model) {
                        return $model->sesi ? $model->sesi->sesi : '-';
                    },

                ],
                [
                    'label' => 'Mata Kuliah',
                    'attribute' => 'sesi_id',
                    'value' => function ($model) {
                        return $model->matakuliah ? $model->matakuliah->nama : '-';
                    },

                ],
                'nilai',
                [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, Mahasiswa $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    },
                    'template' => '{view} {update} {delete} {restore}',
                ],
            ],
        ]); ?>


    </div>