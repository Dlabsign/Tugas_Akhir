<?php

namespace app\controllers;

use app\models\Jadwal;
use app\models\JadwalSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * JadwalController implements the CRUD actions for Jadwal model.
 */
class JadwalController extends Controller
{
    /**
     * @inheritDoc
     */
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


    /**
     * Lists all Jadwal models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new JadwalSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Jadwal model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    // public function actionCreate()
    // {
    //     $model = new Jadwal();
    //     if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
    //         Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    //         if ($model->validate()) {
    //             $model->save(false);
    //             return ['success' => true];
    //         } else {
    //             return [
    //                 'success' => false,
    //                 'errors' => $model->getErrors(),
    //             ];
    //         }
    //     }

    //     // if ($this->request->isPost) {
    //     //     if ($model->load($this->request->post()) && $model->save()) {
    //     //         return $this->redirect(['view', 'id' => $model->id]);
    //     //     }
    //     // } else {
    //     //     $model->loadDefaultValues();
    //     // }

    //     return $this->renderAjax('_form', [
    //         'model' => $model,
    //     ]);
    // }

    // public function actionCreate()
    // {
    //     $model = new Jadwal();

    //     if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
    //         Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    //         if ($model->validate()) {
    //             $model->flag = 1;
    //             $model->save(false);
    //             return ['success' => true];
    //         } else {
    //             return ['success' => false, 'errors' => $model->getErrors()];
    //         }
    //     }

    //     return $this->renderAjax('_form', ['model' => $model]);
    // }


    public function actionCreate()
    {
        $model = new Jadwal();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($model->validate()) {
                // Pengecekan jadwal bentrok (hanya jadwal aktif dengan flag = 1)
                $conflict = Jadwal::find()
                    ->where([
                        'tanggal_jadwal' => $model->tanggal_jadwal,
                        'laboratorium_id' => $model->laboratorium_id,
                        'flag' => 1
                    ])
                    ->andWhere([
                        'or',
                        // Waktu mulai berada di tengah jadwal lain
                        ['between', 'waktu_mulai', $model->waktu_mulai, $model->waktu_selesai],
                        // Waktu selesai berada di tengah jadwal lain
                        ['between', 'waktu_selesai', $model->waktu_mulai, $model->waktu_selesai],
                        // Jadwal lain menutupi seluruh rentang waktu
                        ['and', ['<=', 'waktu_mulai', $model->waktu_mulai], ['>=', 'waktu_selesai', $model->waktu_selesai]]
                    ])
                    ->exists();

                if ($conflict) {
                    return [
                        'success' => false,
                        'errors' => ['jadwal' => ['Jadwal bentrok dengan jadwal lain di laboratorium yang sama.']]
                    ];
                }

                $model->flag = 1;
                $model->save(false);
                return ['success' => true];
            } else {
                return ['success' => false, 'errors' => $model->getErrors()];
            }
        }
        return $this->renderAjax('_form', ['model' => $model]);
    }



    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ($model->validate()) {
                $model->save(false);
                return ['success' => true];
            } else {
                return ['success' => false, 'errors' => $model->getErrors()];
            }
        }

        return $this->renderAjax('_form', ['model' => $model]);
    }


    /**
     * Deletes an existing Jadwal model.
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
     * Finds the Jadwal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Jadwal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Jadwal::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
