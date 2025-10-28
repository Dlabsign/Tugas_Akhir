<?php

namespace tests\unit\models;

use app\models\Laboratorium;
use Yii;

class LaboratoriumTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        // Reset data dummy sebelum setiap test
        Laboratorium::deleteAll();
    }

    /** -------------------------------
     *  CASE 1: Input Ruangan Sukses
     *  ------------------------------- */
    public function testInputRuanganSukses()
    {
        $lab = new Laboratorium();
        $lab->nama = "Lab Riset AI";
        $lab->ruang = "G.501";

        $this->assertTrue($lab->validate(), 'Data harus valid');
        $this->assertTrue($lab->save(), 'Data berhasil disimpan');
        $this->assertEquals(1, $lab->flag, 'Flag otomatis di-set ke 1');
    }

    /** ---------------------------------------
     *  CASE 2: Input Ruangan Gagal (Sudah dipakai)
     *  --------------------------------------- */
    public function testInputRuanganGagalSudahDipakai()
    {
        // Simulasi lab pertama yang sudah memakai ruang K.310
        $lab1 = new Laboratorium([
            'nama' => 'Lab Sic',
            'ruang' => 'K.123123'
        ]);
        $lab1->save();

        // Input lab kedua yang ingin memakai ruang sama
        $lab2 = new Laboratorium([
            'nama' => 'Lab KCVK',
            'ruang' => 'K.123123'
        ]);

        // Secara default model tidak punya rule unik di kolom 'ruang'
        // jadi validasi manual perlu dilakukan
        $isRuanganDipakai = Laboratorium::find()
            ->where(['ruang' => $lab2->ruang, 'flag' => 1])
            ->exists();

        $this->assertTrue($isRuanganDipakai, 'Ruang sudah dipakai Lab Sic');
        if ($isRuanganDipakai) {
            $lab2->addError('ruang', 'Ruangan sudah dipakai.');
        }

        $this->assertFalse($lab2->save(), 'Data tidak boleh disimpan');
        $this->assertArrayHasKey('ruang', $lab2->errors);
    }

    /** -----------------------------------
     *  CASE 3: Input Ruangan Gagal (Kosong)
     *  ----------------------------------- */
    public function testInputRuanganGagalKosong()
    {
        $lab = new Laboratorium();
        $lab->nama = "Lab Big Data";
        $lab->ruang = ""; // dikosongkan

        $isValid = $lab->validate();

        // Sekarang HARUS false karena 'ruang' required
        $this->assertFalse($isValid, 'Validasi harus gagal karena ruang kosong');
        $this->assertArrayHasKey('ruang', $lab->errors);
    }
}
