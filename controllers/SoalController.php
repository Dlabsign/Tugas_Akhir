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


    // public function actionCreate()
    // {
    //     $modelsSoal = [new Detail_soal()];

    //     if (Yii::$app->request->isPost) {
    //         $modelsSoal = Detail_soal::createMultiple(Detail_soal::class);
    //         Model::loadMultiple($modelsSoal, Yii::$app->request->post());

    //         $valid = Model::validateMultiple($modelsSoal);

    //         if ($valid) {
    //             $sesiId     = $modelsSoal[0]->sesi_id;
    //             $kodeSoal   = $modelsSoal[0]->kode_soal;

    //             foreach ($modelsSoal as $modelSoal) {
    //                 $modelSoal->sesi_id = $sesiId;
    //                 $modelSoal->kode_soal = $kodeSoal;

    //                 $jadwal = \app\models\Jadwal::findOne($sesiId);
    //                 if ($jadwal) {
    //                     $modelSoal->matakuliah_id = $jadwal->matakuliah_id;
    //                 }

    //                 $modelSoal->save(false);
    //             }
    //         }
    //     }


    //     return $this->render('create', [
    //         'modelsSoal' => $modelsSoal,
    //     ]);
    // }


    public function actionCreate()
    {
        $modelsSoal = [new Detail_soal()];

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if (!empty($post['Detail_soal'])) {
                $modelsSoal[0]->load($post);
            }
            if (!empty($post['soal'])) {
                foreach ($post['soal'] as $data) {
                    $model = new Detail_soal();
                    $model->attributes = $data;
                    $model->sesi_id = $post['Detail_soal']['sesi_id'] ?? null;
                    $model->kode_soal = $post['Detail_soal']['kode_soal'] ?? null;
                    if ($model->sesi_id) {
                        $jadwal = \app\models\Jadwal::findOne($model->sesi_id);
                        if ($jadwal) {
                            $model->matakuliah_id = $jadwal->matakuliah_id;
                        }
                    }

                    $model->save(false);
                }
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'modelsSoal' => $modelsSoal,
        ]);
    }


    //lihat kode soal
    public function actionViewByKode($kode_soal)
    {
        $soalList = (new \yii\db\Query())
            ->select(['id', 'kode_soal', 'teks_soal', 'skor_maks'])
            ->from('detail_soal')   // pakai tabel detail_soal
            ->where(['kode_soal' => $kode_soal])
            ->all();

        return $this->render('view-by-kode', [
            'soalList' => $soalList,
            'kode_soal' => $kode_soal,
        ]);
    }

    public function actionDetailByKode($kode)
    {
        $soalList = (new \yii\db\Query())
            ->from('soal')
            ->where(['kode_soal' => $kode])
            ->all();

        return $this->render('detail-by-kode', [
            'soalList' => $soalList,
            'kode' => $kode,
        ]);
    }


   
    public function actionUpdate($id)
    {
        $modelUtama = $this->findModel($id);
       
        // ambil semua soal terkait kode_soal
        $modelsSoal = Detail_soal::find()
            ->where(['kode_soal' => $modelUtama->kode_soal])
            ->all();

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            if (!empty($post['Detail_soal']) && is_array($post['Detail_soal'])) {
                $sentIds = []; // untuk track id yang dikirim (untuk hapus sisanya)

                foreach ($post['Detail_soal'] as $key => $data) {
                    if (!is_numeric($key) || !is_array($data)) {
                        continue;
                    }

                    $teks = trim($data['teks_soal'] ?? '');
                    $skor = trim((string)($data['skor_maks'] ?? ''));
                    if ($teks === '' && $skor === '') {
                        continue;
                    }

                    if (!empty($data['id'])) {
                        // UPDATE
                        $model = Detail_soal::findOne($data['id']);
                        if ($model) {
                            $model->load(['Detail_soal' => $data]);
                            if (empty($model->sesi_id)) {
                                $model->sesi_id = $post['Detail_soal'][0]['sesi_id'] ?? $modelUtama->sesi_id;
                            }
                            if (empty($model->kode_soal)) {
                                $model->kode_soal = $modelUtama->kode_soal;
                            }
                            if ($model->sesi_id) {
                                $jadwal = \app\models\Jadwal::findOne($model->sesi_id);
                                if ($jadwal) {
                                    $model->matakuliah_id = $jadwal->matakuliah_id;
                                }
                            }
                            $model->save(false);
                            $sentIds[] = (int)$model->id;
                        }
                    } else {
                        // CREATE BARU
                        $model = new Detail_soal();
                        $model->load(['Detail_soal' => $data]);
                        $model->sesi_id = $data['sesi_id'] ?? ($post['Detail_soal'][0]['sesi_id'] ?? $modelUtama->sesi_id);
                        $model->kode_soal = $data['kode_soal'] ?? $modelUtama->kode_soal;
                        if ($model->sesi_id) {
                            $jadwal = \app\models\Jadwal::findOne($model->sesi_id);
                            if ($jadwal) {
                                $model->matakuliah_id = $jadwal->matakuliah_id;
                            }
                        }
                        $model->save(false);
                        $sentIds[] = (int)$model->id;
                    }
                }

                // DELETE yang dihapus dari UI
                if (!empty($sentIds)) {
                    Detail_soal::deleteAll([
                        'and',
                        ['kode_soal' => $modelUtama->kode_soal],
                        ['not in', 'id', $sentIds]
                    ]);
                } else {
                    Detail_soal::deleteAll(['kode_soal' => $modelUtama->kode_soal]);
                }

                return $this->redirect(['view-by-kode', 'kode_soal' => $modelUtama->kode_soal]);
            }
        }

        return $this->render('update', [
            'modelsSoal' => $modelsSoal,
            'modelUtama' => $modelUtama,
        ]);
    }



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
