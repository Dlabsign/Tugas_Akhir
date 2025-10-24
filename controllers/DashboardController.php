<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;
use yii\filters\VerbFilter;
use app\models\Jadwal;
use app\models\Mahasiswa;
use app\models\Matakuliah;
use app\models\JadwalSearch;
use yii\db\Expression;

class DashboardController extends Controller
{
    public function actionIndex()
    {
        $jumlahMahasiswa = Mahasiswa::find()->where(['flag' => 1])->count();
        $jumlahSesi = Jadwal::find()->where(['flag' => 1])->count();
        $jumlahMatakuliah = Matakuliah::find()->where(['flag' => 1])->count();

        date_default_timezone_set('Asia/Jakarta');
        $currentDateTime = date('Y-m-d H:i:s');

        $sesiBerlangsung = Jadwal::find()
            ->where(new Expression('CONCAT(tanggal_jadwal, " ", waktu_mulai) <= :now'), [':now' => $currentDateTime])
            ->andWhere(new Expression('CONCAT(tanggal_jadwal, " ", waktu_selesai) >= :now'), [':now' => $currentDateTime])
            ->all();

        return $this->render('index', [
            'jumlahMahasiswa' => $jumlahMahasiswa,
            'jumlahSesi' => $jumlahSesi,
            'jumlahMatakuliah' => $jumlahMatakuliah,
            'sesiBerlangsung' => $sesiBerlangsung,

        ]);
    }
}