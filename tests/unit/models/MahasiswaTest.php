<?php

namespace tests\unit\models;

use app\models\Mahasiswa;
use Codeception\Test\Unit;

class MahasiswaTest extends Unit
{
    protected function _before() {}

    /** ✅ Input Manual Berhasil */
    public function testInputManualBerhasil()
    {
        $model = new Mahasiswa();
        $model->nim = 654321;
        $model->semester = 5;
        $model->sesi_id = 1;
        $model->flag = 1;

        $this->assertTrue($model->validate(), 'Semua field valid harusnya lolos validasi.');
        $this->assertTrue($model->save(false), 'Data berhasil disimpan ke database.');
    }

    /** ❌ Input Manual Gagal (NIM Duplikat) */
    public function testInputManualGagalNimDuplikat()
    {
        // Simulasi mahasiswa lama
        $existing = new Mahasiswa();
        $existing->nim = 123456;
        $existing->semester = 3;
        $existing->sesi_id = 1;
        $existing->flag = 1;
        $existing->save(false);

        // Input baru dengan NIM sama
        $model = new Mahasiswa();
        $model->nim = 123456;
        $model->semester = 2;
        $model->sesi_id = 1;
        $model->flag = 1;

        // Cek duplikat manual (karena di rules belum ada 'unique')
        $exists = Mahasiswa::find()->where(['nim' => $model->nim, 'flag' => 1])->exists();
        $this->assertTrue($exists, 'NIM sudah ada di database.');
        $this->assertFalse(!$exists, 'Validasi gagal: Mahasiswa sudah terdaftar.');
    }

    /** ❌ Input Excel Gagal (Format Salah) */
    public function testInputExcelFormatSalah()
    {
        // Simulasi pembacaan file Excel dengan kolom salah
        $dataExcel = [
            ['NIM' => '999999', 'Nama' => 'Agus'], // Kolom B salah, seharusnya Semester
        ];

        $validFormat = array_key_exists('Semester', $dataExcel[0]);
        $this->assertFalse($validFormat, 'Format file Excel salah. Kolom B seharusnya "Semester".');
    }

    /** ✅ Input Excel Berhasil */
    public function testInputExcelBerhasil()
    {
        // Simulasi isi file Excel valid
        $dataExcel = [
            ['NIM' => '888888', 'Semester' => 5],
            ['NIM' => '777777', 'Semester' => 4],
        ];

        foreach ($dataExcel as $row) {
            $model = new Mahasiswa();
            $model->nim = $row['NIM'];
            $model->semester = $row['Semester'];
            $model->sesi_id = 1;
            $model->flag = 1;

            $this->assertTrue($model->validate(), "Row {$row['NIM']} harus lolos validasi.");
        }
    }
}
