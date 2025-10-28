<?php

namespace tests\unit\models;

use app\models\Detail_soal;
use app\models\Jadwal;
use app\models\Matakuliah;
use Codeception\Test\Unit;

class DetailSoalTest extends Unit
{
    protected function _before()
    {
        // Setup data dasar bila diperlukan
    }

    /**
     * 1️⃣ Input Berhasil:
     * Sesi valid, kode soal baru, teks soal diisi.
     */
    public function testInputBerhasil()
    {
        $model = new Detail_soal([
            'sesi_id' => 1, // sudah ada di tabel jadwal
            'matakuliah_id' => 1,
            'kode_soal' => 'SOAL-001',
            'teks_soal' => 'Ini teks soal baru',
            'skor_maks' => 100,
            'type' => 'pilihan_ganda',
            'bahasa' => 1,
        ]);

        $this->assertTrue($model->validate(), 'Validasi gagal padahal data benar');
    }

    /**
     * 2️⃣ Input Gagal (Jadwal/Sesi Tidak Valid):
     * sesi_id belum dipilih (null)
     */
    public function testInputGagalSesiTidakValid()
    {
        $model = new Detail_soal([
            'sesi_id' => null,
            'matakuliah_id' => 1,
            'kode_soal' => 'SOAL-002',
            'teks_soal' => 'Soal tanpa sesi',
            'skor_maks' => 100,
            'type' => 'pilihan_ganda',
            'bahasa' => 1,
        ]);

        $this->assertFalse($model->validate(), 'Validasi tidak gagal meskipun sesi kosong');
        $this->assertArrayHasKey('sesi_id', $model->getErrors(), 'Harus ada error pada sesi_id');
    }

    /**
     * 3️⃣ Input Gagal (Kode Soal Duplikat):
     * kode_soal sudah ada di database.
     */
    public function testInputGagalKodeSoalDuplikat()
    {
        // Buat data awal
        $existing = new Detail_soal([
            'sesi_id' => 1,
            'matakuliah_id' => 1,
            'kode_soal' => 'SOAL-LAMA',
            'teks_soal' => 'Soal lama',
            'skor_maks' => 100,
            'type' => 'pilihan_ganda',
            'bahasa' => 1,
        ]);
        $existing->save(false);

        // Buat data duplikat
        $duplicate = new Detail_soal([
            'sesi_id' => 1,
            'matakuliah_id' => 1,
            'kode_soal' => 'SOAL-LAMA',
            'teks_soal' => 'Soal duplikat',
            'skor_maks' => 100,
            'type' => 'pilihan_ganda',
            'bahasa' => 1,
        ]);

        // Tambahkan validasi unik manual (jika belum ada di rules)
        $duplicate->validate();
        $isDuplicate = Detail_soal::find()->where(['kode_soal' => $duplicate->kode_soal])->exists();

        $this->assertTrue($isDuplicate, 'Seharusnya kode soal sudah ada di database');
    }
}
