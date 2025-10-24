<?php

namespace app\controllers;

use app\models\Mahasiswa;
use app\models\MahasiswaSearch;
use app\models\UploadExcelForm;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\filters\AccessControl;
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

                    $nim = trim($row[0]); // kolom A
                    $semester = trim($row[1]); // kolom B

                    if (!empty($nim) && !empty($semester)) {
                        $mahasiswa = new Mahasiswa();
                        $mahasiswa->nim = $nim;
                        $mahasiswa->semester = $semester;
                        $mahasiswa->kode_soal = $model->kode_soal;
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

    // public function actionCreateManual()
    // {
    //     $model = new Mahasiswa();

    //     if ($this->request->isPost && $model->load($this->request->post())) {
    //         if ($model->save()) {
    //             Yii::$app->session->setFlash('success', 'Data mahasiswa berhasil disimpan.');
    //             return $this->redirect(['index']);
    //         }
    //     }

    //     return $this->render('upload_manual', ['model' => $model]);
    // }

    public function actionCreateManual()
    {
        $model = new Mahasiswa();

        if ($this->request->isPost && $model->load($this->request->post())) {

            // 🔍 Cek apakah mahasiswa sudah terdaftar di sesi & kode soal yang sama
            $mahasiswaTerdaftar = Mahasiswa::find()
                ->where([
                    'nim' => $model->nim,
                    'kode_soal' => $model->kode_soal,
                    'sesi_id' => $model->sesi_id,
                    'flag' => 1, // hanya data aktif
                ])
                ->exists();

            if ($mahasiswaTerdaftar) {
                Yii::$app->session->setFlash('error', 'Mahasiswa sudah terdaftar pada sesi dan kode soal yang sama.');
                return $this->redirect(['index']); // arahkan kembali ke index atau bisa juga render ulang form
            }

            // 💾 Jika belum terdaftar, lanjut simpan
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data mahasiswa berhasil disimpan.');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Terjadi kesalahan saat menyimpan data.');
            }
        }

        return $this->renderAjax('upload_manual', ['model' => $model]);
    }



    // public function actionCreateExcel()
    // {
    //     $uploadModel = new UploadExcelForm();

    //     if (Yii::$app->request->isPost) {
    //         $uploadModel->excelFile = UploadedFile::getInstance($uploadModel, 'excelFile');

    //         if ($uploadModel->excelFile && $uploadModel->excelFile->extension === 'xlsx' && $uploadModel->upload()) {
    //             $filePath = Yii::getAlias('@webroot/uploads/') . $uploadModel->excelFile->baseName . '.' . $uploadModel->excelFile->extension;

    //             try {
    //                 $spreadsheet = IOFactory::load($filePath);
    //                 $worksheet = $spreadsheet->getActiveSheet();
    //                 $highestRow = $worksheet->getHighestRow();

    //                 $kode_soal = Yii::$app->request->post('UploadExcelForm')['kode_soal'] ?? null;

    //                 for ($row = 2; $row <= $highestRow; $row++) {
    //                     $nim = $worksheet->getCell('A' . $row)->getValue();
    //                     $semester = $worksheet->getCell('B' . $row)->getValue();

    //                     if (empty($nim)) {
    //                         continue;
    //                     }
    //                     $existingMahasiswa = Mahasiswa::findOne(['nim' => $nim]);
    //                     if ($existingMahasiswa === null) {
    //                         $mahasiswa = new Mahasiswa();
    //                         $mahasiswa->nim = $nim;
    //                         $mahasiswa->semester = $semester;
    //                         $mahasiswa->kode_soal = $kode_soal;
    //                         $mahasiswa->save(false);
    //                     }
    //                 }
    //                 Yii::$app->session->setFlash('success', 'Data dari file Excel berhasil diimpor.');
    //                 @unlink($filePath);
    //                 return $this->redirect(['index']);
    //             } catch (\Exception $e) {
    //                 Yii::$app->session->setFlash('error', 'Terjadi kesalahan saat memproses file: ' . $e->getMessage());
    //             }
    //         } else {
    //             Yii::$app->session->setFlash('error', 'Hanya file .xlsx yang diperbolehkan.');
    //         }
    //     }

    //     return $this->render('upload_excel', [
    //         'uploadModel' => $uploadModel,
    //     ]);
    // }

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

                    // Ambil kode_soal dari form
                    $kode_soal = Yii::$app->request->post('UploadExcelForm')['kode_soal'] ?? null;

                    // Pengecekan apakah sesi_id disertakan di form (jika perlu)
                    $sesi_id = Yii::$app->request->post('UploadExcelForm')['sesi_id'] ?? null;

                    $jumlahBaru = 0;
                    $jumlahDuplikat = 0;

                    for ($row = 2; $row <= $highestRow; $row++) {
                        $nim = $worksheet->getCell('A' . $row)->getValue();
                        $semester = $worksheet->getCell('B' . $row)->getValue();

                        if (empty($nim)) {
                            continue;
                        }

                        // 🔍 Cek apakah mahasiswa sudah terdaftar dengan kode soal yang sama
                        $mahasiswaTerdaftar = Mahasiswa::find()
                            ->where([
                                'nim' => $nim,
                                'kode_soal' => $kode_soal,
                                'flag' => 1,
                            ])
                            ->exists();

                        if ($mahasiswaTerdaftar) {
                            $jumlahDuplikat++;
                            continue; // Skip mahsiswa
                        }
                        // 💾 Simpan data baru
                        $mahasiswa = new Mahasiswa();
                        $mahasiswa->nim = $nim;
                        $mahasiswa->semester = $semester;
                        $mahasiswa->kode_soal = $kode_soal;
                        $mahasiswa->sesi_id = $sesi_id;
                        $mahasiswa->save(false);
                        $jumlahBaru++;
                    }

                    // 🟢 notif
                    Yii::$app->session->setFlash(
                        'success',
                        "Import selesai. {$jumlahBaru} mahasiswa baru ditambahkan, {$jumlahDuplikat} duplikat diabaikan."
                    );

                    @unlink($filePath);
                    return $this->redirect(['index']);
                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('error', 'Terjadi kesalahan saat memproses file: ' . $e->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('error', 'Hanya file .xlsx yang diperbolehkan.');
            }
        }

        return $this->renderAjax('upload_excel', [
            'uploadModel' => $uploadModel,
        ]);
    }



    public function actionUpdate($id)
    {
        $model = Mahasiswa::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('Data tidak ditemukan.');
        }

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data mahasiswa berhasil diperbarui.');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Terjadi kesalahan saat menyimpan data.');
            }
        }

        // 🔑 renderAjax supaya tampil dalam modal
        return $this->renderAjax('upload_manual', ['model' => $model]);
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
