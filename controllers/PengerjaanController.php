<?php

namespace app\controllers;

use app\models\Detail_soal;
use app\models\Detail_soalSearch;
use app\models\Jadwal;
use yii\web\Response; // <-- BARIS INI YANG MEMPERBAIKI ERROR

use app\models\Mahasiswa;
use app\models\Pengerjaan;
use app\models\PengerjaanSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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

    // NIlai Akhir
    // public function actionPenilaianAkhir()
    // {
    //     $query = \app\models\Mahasiswa::find()
    //         ->joinWith(['pengerjaan'])
    //         ->select([
    //             'mahasiswa.*',
    //             'pengerjaan.skor'
    //         ]);

    //     $dataProvider = new \yii\data\ActiveDataProvider([
    //         'query' => $query,
    //     ]);

    //     return $this->render('penilaian-akhir', [
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }


    public function actionPenilaianAkhir()
    {
        // Query ini sekarang menghitung rata-rata skor AI untuk setiap mahasiswa
        $query = Mahasiswa::find()
            ->select([
                'mahasiswa.*', // Ambil semua kolom dari tabel mahasiswa
                'AVG(pengerjaan.skor) AS avg_skor' // Hitung rata-rata dan beri nama 'avg_skor'
            ])
            ->joinWith('pengerjaan', false, 'LEFT JOIN') // Selalu gunakan LEFT JOIN
            ->groupBy(['mahasiswa.id']); // Kelompokkan berdasarkan ID mahasiswa

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('penilaian-akhir', [
            'dataProvider' => $dataProvider,
        ]);
    }


    // public function actionUpdateNilai()
    // {
    //     Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    //     $id = Yii::$app->request->post('id');
    //     $field = Yii::$app->request->post('field');
    //     $value = Yii::$app->request->post('value');

    //     $model = \app\models\Mahasiswa::findOne($id);
    //     if (!$model) {
    //         return ['success' => false, 'message' => 'Mahasiswa tidak ditemukan'];
    //     }

    //     if (!in_array($field, ['nilai_sikap', 'nilai_kedisiplinan'])) {
    //         return ['success' => false, 'message' => 'Kolom tidak valid'];
    //     }

    //     $model->$field = (int)$value;
    //     if ($model->save(false)) {
    //         return ['success' => true];
    //     }

    //     return ['success' => false, 'message' => 'Gagal menyimpan'];
    // }

    public function actionUpdateNilaiManual()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $id = $request->post('id'); 
            $field = $request->post('field'); 
            $value = $request->post('value');
            $mahasiswa = \app\models\Mahasiswa::findOne($id);
            if (!$mahasiswa) {
                return ['status' => 'error', 'message' => 'Mahasiswa tidak ditemukan'];
            }
            if ($field === 'nilai_sikap' || $field === 'nilai_kedisiplinan') {
                $mahasiswa->{$field} = $value;
            } else {
                return ['status' => 'error', 'message' => 'Field tidak valid'];
            }
            $skor_ai_raw = Pengerjaan::find()
                ->where(['mahasiswa_id' => $id])
                ->average('skor');
            $skor_ai = $skor_ai_raw ?? 0;
            $skor_sikap = $mahasiswa->nilai_sikap ?? 0;
            $skor_disiplin = $mahasiswa->nilai_kedisiplinan ?? 0;
            $skor_manual = ($skor_sikap + $skor_disiplin) / 2;
            $nilai_akhir = ($skor_manual * 0.7) + ($skor_ai * 0.3);
            $mahasiswa->nilai_akhir = $nilai_akhir;
            if ($mahasiswa->save(false)) {
                return [
                    'status' => 'success',
                    'message' => 'Tersimpan',
                    'nilai_akhir' => round($nilai_akhir, 2) // Kirim kembali nilai akhir yang baru
                ];
            } else {
                return ['status' => 'error', 'message' => 'Gagal menyimpan', 'errors' => $mahasiswa->errors];
            }
        }
        return ['status' => 'error', 'message' => 'Invalid request'];
    }
    // public function actionUpdateNilaiManual()
    // {
    //     Yii::$app->response->format = Response::FORMAT_JSON;
    //     $request = Yii::$app->request;

    //     if ($request->isAjax) {
    //         $id = $request->post('id'); // ID Mahasiswa
    //         $field = $request->post('field'); // 'nilai_sikap' atau 'nilai_kedisiplinan'
    //         $value = $request->post('value');

    //         // Gunakan namespace lengkap untuk model Mahasiswa
    //         $mahasiswa = \app\models\Mahasiswa::findOne($id);

    //         if (!$mahasiswa) {
    //             return ['status' => 'error', 'message' => 'Mahasiswa tidak ditemukan'];
    //         }

    //         // 1. Update nilai manual yang diubah
    //         if ($field === 'nilai_sikap' || $field === 'nilai_kedisiplinan') {
    //             $mahasiswa->{$field} = $value;
    //         } else {
    //             return ['status' => 'error', 'message' => 'Field tidak valid'];
    //         }

    //         // 2. Ambil komponen nilai
    //         // --- PERBAIKAN PENTING: Hitung rata-rata skor AI ---
    //         // Ini mengambil rata-rata dari SEMUA pengerjaan mahasiswa
    //         $skor_ai_raw = Pengerjaan::find()
    //             ->where(['mahasiswa_id' => $id])
    //             ->average('skor');

    //         $skor_ai = $skor_ai_raw ?? 0;
    //         // --- AKHIR PERBAIKAN ---

    //         $skor_sikap = $mahasiswa->nilai_sikap ?? 0;
    //         $skor_disiplin = $mahasiswa->nilai_kedisiplinan ?? 0;

    //         // 3. Hitung Skor Manual (Rata-rata sikap & disiplin)
    //         $skor_manual = ($skor_sikap + $skor_disiplin) / 2;

    //         // 4. Hitung Nilai Akhir Hibrida (70% Manual, 30% AI)
    //         $nilai_akhir = ($skor_manual * 0.7) + ($skor_ai * 0.3);

    //         // 5. Simpan nilai akhir ke database
    //         $mahasiswa->nilai_akhir = $nilai_akhir;

    //         // Gunakan save(false) sesuai kode yang Anda berikan
    //         if ($mahasiswa->save(false)) {
    //             return [
    //                 'status' => 'success',
    //                 'message' => 'Tersimpan',
    //                 'nilai_akhir' => round($nilai_akhir, 2) // Kirim kembali nilai akhir yang baru
    //             ];
    //         } else {
    //             return ['status' => 'error', 'message' => 'Gagal menyimpan', 'errors' => $mahasiswa->errors];
    //         }
    //     }

    //     return ['status' => 'error', 'message' => 'Invalid request'];
    // }

    public function actionNilaiPerSesi()
    {
        $sesi = Jadwal::find()->all();
        $data = [];

        foreach ($sesi as $s) {
            $pengerjaan = Pengerjaan::find()
                ->where(['jadwal_id' => $s->id])
                ->joinWith('mahasiswa')
                ->all();

            $data[] = [
                'sesi' => $s,
                'pengerjaan' => $pengerjaan
            ];
        }

        return $this->render('nilai-per-sesi', [
            'data' => $data,
        ]);
    }


    // Nilai Soal
    public function actionPenilaianSoal()
    {
        $soalList = \app\models\Detail_soal::find()
            ->with(['matakuliah', 'sesi'])
            ->all();

        $groupedSoal = [];

        foreach ($soalList as $soal) {
            $mkId = $soal->matakuliah_id;
            $sesiId = $soal->sesi_id;

            if (!isset($groupedSoal[$mkId])) {
                $groupedSoal[$mkId] = [
                    'matakuliah_nama' => $soal->matakuliah ? $soal->matakuliah->nama : 'Tanpa Matakuliah',
                    'sessions' => [],
                ];
            }

            if (!isset($groupedSoal[$mkId]['sessions'][$sesiId])) {
                $groupedSoal[$mkId]['sessions'][$sesiId] = [
                    'waktu_display' => $soal->sesi ? $soal->sesi->waktu_mulai . ' – ' . $soal->sesi->waktu_selesai : '-',
                    'soal_list' => [],
                    'soal_by_kode' => [],
                ];
            }

            $groupedSoal[$mkId]['sessions'][$sesiId]['soal_list'][] = $soal;
            $groupedSoal[$mkId]['sessions'][$sesiId]['soal_by_kode'][$soal->kode_soal] = [
                'kode_soal' => $soal->kode_soal
            ];
        }

        return $this->render('penilaian-soal', [
            'groupedSoal' => $groupedSoal
        ]);
    }


    public function actionIndex()
    {
        $soalList = \app\models\Detail_soal::find()
            ->with(['matakuliah', 'sesi'])
            ->all();

        $groupedSoal = [];

        foreach ($soalList as $soal) {
            $mkId = $soal->matakuliah_id;
            $sesiId = $soal->sesi_id;

            if (!isset($groupedSoal[$mkId])) {
                $groupedSoal[$mkId] = [
                    'matakuliah_nama' => $soal->matakuliah ? $soal->matakuliah->nama : 'Tanpa Matakuliah',
                    'sessions' => [],
                ];
            }

            if (!isset($groupedSoal[$mkId]['sessions'][$sesiId])) {
                $groupedSoal[$mkId]['sessions'][$sesiId] = [
                    'waktu_display' => $soal->sesi ? $soal->sesi->waktu_mulai . ' – ' . $soal->sesi->waktu_selesai : '-',
                    'soal_list' => [],
                    'soal_by_kode' => [],
                ];
            }

            $groupedSoal[$mkId]['sessions'][$sesiId]['soal_list'][] = $soal;
            $groupedSoal[$mkId]['sessions'][$sesiId]['soal_by_kode'][$soal->kode_soal] = [
                'kode_soal' => $soal->kode_soal
            ];
        }

        return $this->render('index', [
            'groupedSoal' => $groupedSoal
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

    public function actionDetailSoal($kode_soal)
    {
        // ambil semua detail soal berdasarkan kode_soal
        $soalUtama = \app\models\Detail_soal::find()->where(['kode_soal' => $kode_soal])->one();
        if (!$soalUtama) {
            throw new \yii\web\NotFoundHttpException("Kode soal tidak ditemukan");
        }
        $query = \app\models\Detail_soal::find()
            ->where(['kode_soal' => $kode_soal]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('detail-soal', [
            'dataProvider' => $dataProvider,
            'soal' => $soalUtama, // ✅ dikirim ke view
        ]);
    }

    public function actionTestGemini()
    {
        $result = \Yii::$app->gemini->generateText("Halo Gemini, ceritakan tentang AI!");
        var_dump($result);
    }


    public function actionDetailJawaban($id)
    {
        // $id = id dari detail_soal
        $soal = \app\models\Detail_soal::findOne($id);
        if (!$soal) {
            throw new \yii\web\NotFoundHttpException("Soal tidak ditemukan");
        }

        // ambil jawaban mahasiswa dari tabel pengerjaan
        $query = \app\models\Pengerjaan::find()
            ->where(['soal_id' => $soal->id])
            ->with('mahasiswa'); // biar bisa akses nim/nama mahasiswa

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        return $this->render('detail-jawaban', [
            'soal' => $soal,
            'dataProvider' => $dataProvider,
        ]);
    }


    // fungsi nilai tetap bisa dipakai
    public function actionNilai($id)
    {
        $model = Pengerjaan::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException("Pengerjaan tidak ditemukan.");
        }

        if (!$model->jawaban_teks) {
            Yii::$app->session->setFlash('error', 'Jawaban kosong, tidak bisa dinilai.');
            return $this->redirect(Yii::$app->request->referrer ?: ['index']);
        }

        try {
            $teksSoal = $model->detailSoal->teks_soal ?? "(soal tidak ditemukan)";

            $prompt = "Soal: {$teksSoal}\n\n" .
                "Jawaban siswa: {$model->jawaban_teks}\n\n" .
                "Tugas Anda: Beri umpan balik singkat, objektif, dan jelas apakah jawaban tersebut sudah benar atau salah sesuai soal. 
                Jika salah, jelaskan bagian mana yang kurang tepat. Hilangkan penulisan bold atau bintan **.";

            $feedback = Yii::$app->gemini->generateText($prompt);
            if ($feedback) {
                $model->umpan_balik = $feedback;
                $model->save(false);
                Yii::$app->session->setFlash('success', 'Penilaian berhasil disimpan.');
            } else {
                Yii::$app->session->setFlash('error', 'Gagal mendapatkan feedback dari Gemini.');
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    // public function actionNilai($id)
    // {
    //     $model = Pengerjaan::findOne($id);
    //     if (!$model) {
    //         throw new NotFoundHttpException("Pengerjaan tidak ditemukan.");
    //     }

    //     if (!$model->jawaban_teks) {
    //         Yii::$app->session->setFlash('error', 'Jawaban kosong, tidak bisa dinilai.');
    //         return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    //     }

    //     try {
    //         $teksSoal = $model->detailSoal->teks_soal ?? "(soal tidak ditemukan)";
    //         $jawabanSaatIni = $model->jawaban_teks;

    //         // 🔍 Ambil semua jawaban lain untuk soal yang sama
    //         $jawabanLain = Pengerjaan::find()
    //             ->where(['soal_id' => $model->soal_id])
    //             ->andWhere(['!=', 'id', $model->id])
    //             ->all();

    //         $isDuplicate = false;
    //         $similarNIM = null;

    //         // ✅ Loop dan bandingkan setiap jawaban dengan Gemini
    //         foreach ($jawabanLain as $jLain) {
    //             $promptCheck = "Bandingkan dua jawaban berikut:\n\n" .
    //                 "Jawaban A:\n{$jawabanSaatIni}\n\n" .
    //                 "Jawaban B:\n{$jLain->jawaban_teks}\n\n" .
    //                 "Apakah kedua jawaban ini sangat mirip atau sama? Jawab dengan salah satu: 'YA' jika sangat mirip/duplikat, atau 'TIDAK' jika berbeda.";

    //             $checkResult = Yii::$app->gemini->generateText($promptCheck);

    //             if (stripos($checkResult, 'YA') !== false) {
    //                 $isDuplicate = true;
    //                 $similarNIM = $jLain->mahasiswa->nim ?? null;
    //                 break;
    //             }
    //         }

    //         if ($isDuplicate) {
    //             Yii::$app->session->setFlash(
    //                 'warning',
    //                 "⚠️ Jawaban ini terdeteksi sangat mirip dengan mahasiswa lain (NIM: {$similarNIM}). Harap periksa kemungkinan plagiarisme."
    //             );
    //         }

    //         // 🧠 Lanjutkan proses penilaian normal
    //         $prompt = "Soal: {$teksSoal}\n\n" .
    //             "Jawaban siswa: {$jawabanSaatIni}\n\n" .
    //             "Tugas Anda: Beri umpan balik singkat, objektif, dan jelas apakah jawaban tersebut sudah benar atau salah sesuai soal. 
    //         Jika salah, jelaskan bagian mana yang kurang tepat.";

    //         $feedback = Yii::$app->gemini->generateText($prompt);

    //         if ($feedback) {
    //             $model->umpan_balik = $feedback;

    //             // Opsional: beri tanda duplikasi di DB
    //             if ($isDuplicate) {
    //                 $model->flag = 1; // misal 1 = duplikat
    //             }

    //             $model->save(false);
    //             Yii::$app->session->setFlash('success', 'Penilaian berhasil disimpan.');
    //         } else {
    //             Yii::$app->session->setFlash('error', 'Gagal mendapatkan feedback dari Gemini.');
    //         }
    //     } catch (\Exception $e) {
    //         Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
    //     }

    //     return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    // }



    public function actionCekDuplikat($id)
    {
        $soal = \app\models\Detail_soal::findOne($id);
        if (!$soal) {
            throw new \yii\web\NotFoundHttpException("Soal tidak ditemukan.");
        }

        $jawabanList = \app\models\Pengerjaan::find()
            ->where(['soal_id' => $soal->id])
            ->with('mahasiswa')
            ->all();

        if (count($jawabanList) < 2) {
            Yii::$app->session->setFlash('info', 'Minimal dua jawaban dibutuhkan untuk mendeteksi duplikasi.');
            return $this->redirect(['detail-jawaban', 'id' => $soal->id]);
        }

        // Reset flag
        foreach ($jawabanList as $j) {
            $j->flag = 0;
            $j->save(false);
        }

        $duplikatList = [];
        $miripList = [];

        try {
            foreach ($jawabanList as $i => $jawabanA) {
                for ($j = $i + 1; $j < count($jawabanList); $j++) {
                    $jawabanB = $jawabanList[$j];
                    $prompt = "Bandingkan jawaban jawaban berikut:\n\n" .
                        "Jawaban 1 (NIM {$jawabanA->mahasiswa->nim}): {$jawabanA->jawaban_teks}\n\n" .
                        "Jawaban 2 (NIM {$jawabanB->mahasiswa->nim}): {$jawabanB->jawaban_teks}\n\n" .
                        "Tentukan tingkat kesamaan:\n" .
                        "- Jika identik (copy paste), jawab: 'SAMA'.\n" .
                        "- Jika sangat mirip (hampir sama redaksi), jawab: 'MIRIP'.\n" .
                        "- Jika berbeda, jawab: 'BERBEDA'.\n" .
                        "Jawab hanya satu kata.";

                    $response = strtoupper(trim(Yii::$app->gemini->generateText($prompt)));

                    if (strpos($response, 'SAMA') !== false) {
                        $jawabanA->flag = 1;
                        $jawabanB->flag = 1;
                        $jawabanA->save(false);
                        $jawabanB->save(false);
                        $duplikatList[] = "{$jawabanA->mahasiswa->nim} ↔ {$jawabanB->mahasiswa->nim}";
                    } elseif (strpos($response, 'MIRIP') !== false) {
                        if ($jawabanA->flag == 0) $jawabanA->flag = 2;
                        if ($jawabanB->flag == 0) $jawabanB->flag = 2;
                        $jawabanA->save(false);
                        $jawabanB->save(false);
                        $miripList[] = "{$jawabanA->mahasiswa->nim} ↔ {$jawabanB->mahasiswa->nim}";
                    }

                    usleep(500000); // 0.5 detik delay agar tidak overload API
                }
            }

            Yii::$app->session->setFlash('duplikatList', $duplikatList);
            Yii::$app->session->setFlash('miripList', $miripList);

            if ($duplikatList || $miripList) {
                Yii::$app->session->setFlash('success', 'Pengecekan duplikasi selesai. Lihat hasil di bawah.');
            } else {
                Yii::$app->session->setFlash('info', 'Tidak ditemukan jawaban duplikat atau mirip.');
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Error saat memeriksa: ' . $e->getMessage());
        }

        return $this->redirect(['detail-jawaban', 'id' => $soal->id]);
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

    public function actionUpdateSkor()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $skor = Yii::$app->request->post('skor');

        $model = Pengerjaan::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Data tidak ditemukan'];
        }

        $model->skor = $skor;
        if ($model->save(false)) {
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Gagal menyimpan ke database'];
    }
}
