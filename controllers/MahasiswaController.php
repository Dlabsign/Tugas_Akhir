<?php

namespace app\controllers;

use app\models\Mahasiswa;
use app\models\MahasiswaSearch;
use app\models\UploadExcelForm;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * MahasiswaController implements the CRUD actions for Mahasiswa model.
 */
class MahasiswaController extends Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionUploadExcel()
    {
        $model = new UploadExcelForm();

        if (Yii::$app->request->isPost) {
            $model->excelFile = UploadedFile::getInstance($model, 'excelFile');
            if ($model->validate()) {
                // Load file excel
                $spreadsheet = IOFactory::load($model->excelFile->tempName);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();

                // Lewati header (baris pertama)
                foreach ($rows as $i => $row) {
                    if ($i == 0) continue;

                    $nim = trim($row[0]);       // kolom A
                    $semester = trim($row[1]); // kolom B

                    if (!empty($nim) && !empty($semester)) {
                        $mahasiswa = new Mahasiswa();
                        $mahasiswa->nim = $nim;
                        $mahasiswa->semester = $semester;
                        $mahasiswa->sesi_id = $model->sesi_id;
                        $mahasiswa->save(false); // langsung simpan tanpa validasi
                    }
                }

                Yii::$app->session->setFlash('success', 'Data berhasil diimport ke database.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('upload_excel', [
            'uploadModel' => $model,
        ]);
    }



    public function actionIndex()
    {
        $searchModel = new MahasiswaSearch();
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

    public function actionCreate()
    {
        $model = new Mahasiswa();
        $uploadModel = new UploadExcelForm();

        return $this->render('create', [
            'model' => $model,
            'uploadModel' => $uploadModel,
        ]);
    }

    public function actionCreateManual()
    {
        $model = new Mahasiswa();

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data mahasiswa berhasil disimpan.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('upload_manual', ['model' => $model]);
    }




    public function actionCreateExcel()
    {
        $uploadModel = new UploadExcelForm();

        if (Yii::$app->request->isPost) {
            $uploadModel->excelFile = UploadedFile::getInstance($uploadModel, 'excelFile');

            if ($uploadModel->excelFile && $uploadModel->excelFile->extension === 'xlsx' && $uploadModel->upload()) {
                $filePath = Yii::getAlias('@webroot/uploads/') . $uploadModel->excelFile->baseName . '.' . $uploadModel->excelFile->extension;

                try {
                    $spreadsheet = IOFactory::load($filePath);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $highestRow = $worksheet->getHighestRow();

                    $sesi_id = Yii::$app->request->post('UploadExcelForm')['sesi_id'] ?? null;

                    for ($row = 2; $row <= $highestRow; $row++) {
                        $nim = $worksheet->getCell('A' . $row)->getValue();
                        $semester = $worksheet->getCell('B' . $row)->getValue();

                        if (empty($nim)) {
                            continue;
                        }
                        $existingMahasiswa = Mahasiswa::findOne(['nim' => $nim]);
                        if ($existingMahasiswa === null) {
                            $mahasiswa = new Mahasiswa();
                            $mahasiswa->nim = $nim;
                            $mahasiswa->semester = $semester;
                            $mahasiswa->sesi_id = $sesi_id;
                            $mahasiswa->save(false);
                        }
                    }
                    Yii::$app->session->setFlash('success', 'Data dari file Excel berhasil diimpor.');
                    @unlink($filePath);
                    return $this->redirect(['index']);
                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('error', 'Terjadi kesalahan saat memproses file: ' . $e->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('error', 'Hanya file .xlsx yang diperbolehkan.');
            }
        }

        return $this->render('upload_excel', [
            'uploadModel' => $uploadModel,
        ]);
    }


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

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Mahasiswa::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
