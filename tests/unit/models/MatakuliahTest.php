<?php

namespace tests\unit\models;

use app\models\Matakuliah;
use Yii;

class MatakuliahTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        // Bersihkan tabel sebelum setiap test
        Matakuliah::deleteAll();
    }

    /** 
     * Skenario 1: Input Sukses (Data valid, nama unik)
     */
    public function testInputMatakuliahSukses()
    {
        $model = new Matakuliah([
            'nama' => 'Kecerdasan Buatan',
            'semester' => 5
        ]);

        $this->assertTrue($model->validate(), 'Validasi gagal untuk data valid.');
        $this->assertTrue($model->save(), 'Gagal menyimpan data.');

        $saved = Matakuliah::findOne(['nama' => 'Kecerdasan Buatan']);
        $this->assertNotNull($saved, 'Data tidak ditemukan di database.');
        $this->assertEquals(5, $saved->semester);
        $this->assertEquals(1, $saved->flag);
    }

    /**
     * Skenario 2: Mata Kuliah sudah ada (nama duplikat)
     */
    public function testInputMatakuliahDuplikat()
    {
        // Data awal
        $existing = new Matakuliah([
            'nama' => 'Struktur Data',
            'semester' => 3
        ]);
        $existing->save();

        // Coba input ulang dengan nama sama
        $duplicate = new Matakuliah([
            'nama' => 'Struktur Data',
            'semester' => 3
        ]);

        $this->assertFalse($duplicate->validate(), 'Validasi tidak gagal untuk nama duplikat.');
        $this->assertArrayHasKey('nama', $duplicate->getErrors(), 'Error pada nama tidak muncul.');
    }

    /**
     * Skenario 3: Input Mata Kuliah Kosong
     */
    public function testInputMatakuliahKosong()
    {
        $model = new Matakuliah([
            'nama' => '',
            'semester' => null
        ]);

        $this->assertFalse($model->validate(), 'Validasi tidak gagal untuk input kosong.');
        $this->assertArrayHasKey('nama', $model->getErrors(), 'Error nama tidak muncul.');
        $this->assertArrayHasKey('semester', $model->getErrors(), 'Error semester tidak muncul.');
    }
}
