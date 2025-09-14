<?php

namespace app\controllers;

use app\models\Detail_soal;
use app\models\Detail_soalSearch;
use app\models\SoalSearch;
use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SoalController implements the CRUD actions for Soal model.
 */
class SoalController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Soal models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new Detail_soalSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    // public function actionCreate()
    // {
    //     $model = new Soal();

    //     if ($this->request->isPost) {
    //         if ($model->load($this->request->post())) {

    //             // Cari jadwal berdasarkan sesi_id yang dipilih
    //             $jadwal = \app\models\Jadwal::findOne($model->sesi_id);
    //             if ($jadwal !== null) {
    //                 $model->matakuliah_id = $jadwal->matakuliah_id; // otomatis isi matakuliah_id
    //             }

    //             if ($model->save()) {
    //                 return $this->redirect('index');
    //             }
    //         }
    //     } else {
    //         $model->loadDefaultValues();
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }


    public function actionCreate()
    {
        $modelsSoal = [new Detail_soal()];

        if (Yii::$app->request->isPost) {
            $modelsSoal = Detail_soal::createMultiple(Detail_soal::class);
            Model::loadMultiple($modelsSoal, Yii::$app->request->post());

            $valid = Model::validateMultiple($modelsSoal);

            if ($valid) {
                // Ambil nilai umum dari input pertama
                $sesiId     = $modelsSoal[0]->sesi_id;
                $bobotSoal  = $modelsSoal[0]->bobot_soal;
                $kodeSoal   = $modelsSoal[0]->kode_soal;

                foreach ($modelsSoal as $modelSoal) {
                    // set nilai umum ke setiap model
                    $modelSoal->sesi_id = $sesiId;
                    $modelSoal->bobot_soal = $bobotSoal;
                    $modelSoal->kode_soal = $kodeSoal;

                    // ambil matakuliah_id dari jadwal
                    $jadwal = \app\models\Jadwal::findOne($sesiId);
                    if ($jadwal) {
                        $modelSoal->matakuliah_id = $jadwal->matakuliah_id;
                    }

                    $modelSoal->save(false);
                }

                Yii::$app->session->setFlash('success', 'Semua soal berhasil disimpan.');
                return $this->redirect(['index']);
            }
        }


        return $this->render('create', [
            'modelsSoal' => $modelsSoal,
        ]);
    }




    /**
     * Updates an existing Soal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Soal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Soal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Soal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Detail_soal::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
