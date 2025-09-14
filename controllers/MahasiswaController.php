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
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionUpload()
    {
        $model = new UploadExcelForm();

        if (Yii::$app->request->isPost) {
            $model->excelFile = UploadedFile::getInstance($model, 'excelFile');
            if ($model->upload()) {
                // File berhasil diunggah, sekarang kita proses
                $filePath = 'uploads/' . $model->excelFile->baseName . '.' . $model->excelFile->extension;

                try {
                    $spreadsheet = IOFactory::load($filePath);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();

                    // Mulai dari baris ke-2 untuk melewati header
                    for ($row = 2; $row <= $highestRow; $row++) {
                        // Asumsi: Kolom A = nim, Kolom B = semester
                        $nim = $worksheet->getCell('A' . $row)->getValue();
                        $semester = $worksheet->getCell('B' . $row)->getValue();

                        // Validasi sederhana, jika nim kosong, lewati baris
                        if (empty($nim)) {
                            continue;
                        }

                        $mahasiswa = new Mahasiswa();
                        $mahasiswa->nim = $nim;
                        $mahasiswa->semester = $semester;

                        // Simpan data tanpa validasi (asumsi data excel sudah benar)
                        // atau tambahkan validasi jika perlu dengan $mahasiswa->save()
                        if (!$mahasiswa->save(false)) {
                            // Jika ingin ada validasi, hapus `false`
                            // Jika ada error saat menyimpan, tampilkan pesan
                            Yii::$app->session->setFlash('error', "Gagal menyimpan data pada baris {$row}.");
                            return $this->render('upload', ['model' => $model]);
                        }
                    }

                    Yii::$app->session->setFlash('success', 'Data dari file Excel berhasil diimpor.');
                    // Hapus file setelah diproses
                    unlink($filePath);
                    return $this->redirect(['index']); // Arahkan ke halaman index mahasiswa atau halaman lain

                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('error', 'Terjadi kesalahan saat memproses file: ' . $e->getMessage());
                }
            }
        }

        return $this->render('upload', ['model' => $model]);
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

        if ($this->request->isPost) {
            // === Jika tombol submit berasal dari form manual ===
            if (Yii::$app->request->post('Mahasiswa')) {
                if ($model->load($this->request->post()) && $model->save()) {
                    Yii::$app->session->setFlash('success', 'Data mahasiswa berhasil disimpan.');
                    return $this->redirect(['index']);
                }
            }

            // === Jika tombol submit berasal dari form upload Excel ===
            if (Yii::$app->request->post('UploadExcelForm')) {
                $uploadModel->excelFile = UploadedFile::getInstance($uploadModel, 'excelFile');

                if ($uploadModel->excelFile && $uploadModel->upload()) {
                    $filePath = 'uploads/' . $uploadModel->excelFile->baseName . '.' . $uploadModel->excelFile->extension;

                    try {
                        $spreadsheet = IOFactory::load($filePath);
                        $worksheet = $spreadsheet->getActiveSheet();
                        $highestRow = $worksheet->getHighestRow();

                        // ambil sesi_id dari form Excel
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
                        unlink($filePath);
                        return $this->redirect(['index']);
                    } catch (\Exception $e) {
                        Yii::$app->session->setFlash('error', 'Terjadi kesalahan saat memproses file: ' . $e->getMessage());
                    }
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
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
