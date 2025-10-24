<?php

namespace app\controllers;

use app\models\Detail_soal;
use app\models\Pengerjaan;
use app\models\PengerjaanSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class PengerjaanController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'view', 'create', 'update', 'delete'], // aksi yang dibatasi
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // hanya pengguna login
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    // public function actionIndex()
    // {
    //     $searchModel = new PengerjaanSearch();
    //     $dataProvider = $searchModel->search($this->request->queryParams);

    //     return $this->render('index', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }
    

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Pengerjaan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    public function actionTestGemini()
    {
        $result = \Yii::$app->gemini->generateText("Halo Gemini, ceritakan tentang AI!");
        var_dump($result);
    }


    // Kode Sudah Betul
    // public function actionNilai($id)
    // {
    //     $model = $this->findModel($id);
    //     if (!$model->jawaban_teks) {
    //         \Yii::$app->session->setFlash('error', 'Jawaban kosong, tidak bisa dinilai.');
    //         return $this->redirect(['index']);
    //     }

    //     try {
    //         $feedback = \Yii::$app->gemini->generateText(
    //             "Beri umpan balik singkat dan objektif untuk jawaban berikut: {$model->jawaban_teks}"
    //         );

    //         if ($feedback) {
    //             $model->umpan_balik = $feedback;
    //             $model->save(false);
    //             \Yii::$app->session->setFlash('success', 'Penilaian berhasil disimpan.');
    //         } else {
    //             \Yii::$app->session->setFlash('error', 'Gagal mendapatkan feedback dari Gemini.');
    //         }
    //     } catch (\Exception $e) {
    //         \Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
    //     }

    //     return $this->redirect(['index']);
    // }



    public function actionNilai($id)
    {
        $model = $this->findModel($id);

        if (!$model->jawaban_teks) {
            \Yii::$app->session->setFlash('error', 'Jawaban kosong, tidak bisa dinilai.');
            return $this->redirect(['index']);
        }

        try {
            // ambil teks soal dari relasi detail_soal
            $teksSoal = $model->detailSoal->teks_soal ?? "(soal tidak ditemukan)";

            // Prompt gabungan soal + jawaban
            $prompt = "Soal: {$teksSoal}\n\n" .
                "Jawaban siswa: {$model->jawaban_teks}\n\n" .
                "Tugas Anda: Beri umpan balik singkat, objektif, dan jelas apakah jawaban tersebut sudah benar atau salah sesuai soal. 
                   Jika salah, jelaskan bagian mana yang kurang tepat.";

            $feedback = \Yii::$app->gemini->generateText($prompt);

            if ($feedback) {
                $model->umpan_balik = $feedback;
                $model->save(false);
                \Yii::$app->session->setFlash('success', 'Penilaian berhasil disimpan.');
            } else {
                \Yii::$app->session->setFlash('error', 'Gagal mendapatkan feedback dari Gemini.');
            }
        } catch (\Exception $e) {
            \Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }




    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Pengerjaan::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
