<?php

namespace tests\unit\models;

use app\models\Jadwal;
use Yii;

class JadwalTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        Jadwal::deleteAll();
    }

    /**
     * 1️⃣ Input Berhasil (semua valid)
     */
    public function testInputBerhasil()
    {
        $jadwal = new Jadwal([
            'laboratorium_id' => 1,           // K.310
            'matakuliah_id' => 1,             // PCD
            'tanggal_jadwal' => '2025-10-26',
            'waktu_mulai' => '13:00:00',
            'waktu_selesai' => '14:00:00',
            'dibuat_oleh_staff_id' => 1,
            'sesi' => 3
        ]);

        $this->assertTrue($jadwal->validate(), 'Validasi gagal pada data valid.');
        $this->assertTrue($jadwal->save(), 'Gagal menyimpan jadwal valid.');
        $this->assertEquals(1, $jadwal->flag);
    }

    /**
     * 2️⃣ Input (Matkul) Tidak Valid
     */
    public function testMatkulTidakValid()
    {
        $jadwal = new Jadwal([
            'laboratorium_id' => 7,
            'matakuliah_id' => null, // Kosong
            'tanggal_jadwal' => '2025-10-26',
            'waktu_mulai' => '13:00:00',
            'waktu_selesai' => '14:00:00',
            'dibuat_oleh_staff_id' => 1,
            'sesi' => 3
        ]);

        $this->assertFalse($jadwal->validate(), 'Validasi tidak gagal meskipun matakuliah kosong.');
        $this->assertArrayHasKey('matakuliah_id', $jadwal->getErrors(), 'Error matakuliah tidak muncul.');
    }

    /**
     * 3️⃣ Ruangan Tidak Valid
     */
    public function testRuanganTidakValid()
    {
        $jadwal = new Jadwal([
            'laboratorium_id' => null, // Kosong
            'matakuliah_id' => 1,
            'tanggal_jadwal' => '2025-10-26',
            'waktu_mulai' => '13:00:00',
            'waktu_selesai' => '14:00:00',
            'dibuat_oleh_staff_id' => 1,
            'sesi' => 3
        ]);

        $this->assertFalse($jadwal->validate(), 'Validasi tidak gagal meskipun ruangan kosong.');
        $this->assertArrayHasKey('laboratorium_id', $jadwal->getErrors(), 'Error ruangan tidak muncul.');
    }

    /**
     * 4️⃣ Sesi Bentrok
     */
    public function testSesiBentrok()
    {
        // Jadwal awal (sudah ada sesi 1)
        $existing = new Jadwal([
            'laboratorium_id' => 1,
            'matakuliah_id' => 2, // SBD
            'tanggal_jadwal' => '2025-10-26',
            'waktu_mulai' => '14:00:00',
            'waktu_selesai' => '15:00:00',
            'dibuat_oleh_staff_id' => 1,
            'sesi' => 1
        ]);
        $existing->save();

        // Jadwal baru (sesi sama di tanggal dan ruangan yang sama)
        $baru = new Jadwal([
            'laboratorium_id' => 1,
            'matakuliah_id' => 3, // PCD
            'tanggal_jadwal' => '2025-10-26',
            'waktu_mulai' => '14:00:00',
            'waktu_selesai' => '15:00:00',
            'dibuat_oleh_staff_id' => 1,
            'sesi' => 1
        ]);

        $this->assertTrue($baru->validate(), 'Validasi seharusnya lolos secara umum.');

        // Simulasi pengecekan bentrok manual seperti di controller
        $conflict = Jadwal::find()
            ->where([
                'tanggal_jadwal' => $baru->tanggal_jadwal,
                'laboratorium_id' => $baru->laboratorium_id,
                'flag' => 1
            ])
            ->andWhere(['sesi' => $baru->sesi])
            ->exists();

        $this->assertTrue($conflict, 'Sesi bentrok tidak terdeteksi.');
    }

    /**
     * 5️⃣ Jam Bentrok
     */
    public function testJamBentrok()
    {
        // Jadwal awal (J-01)
        $existing = new Jadwal([
            'laboratorium_id' => 1,
            'matakuliah_id' => 2,
            'tanggal_jadwal' => '2025-10-26',
            'waktu_mulai' => '15:00:00',
            'waktu_selesai' => '16:00:00',
            'dibuat_oleh_staff_id' => 1,
            'sesi' => 3
        ]);
        $existing->save();

        // Jadwal baru bentrok waktu
        $baru = new Jadwal([
            'laboratorium_id' => 1,
            'matakuliah_id' => 3,
            'tanggal_jadwal' => '2025-10-26',
            'waktu_mulai' => '15:45:00',
            'waktu_selesai' => '16:15:00',
            'dibuat_oleh_staff_id' => 1,
            'sesi' => 3
        ]);

        $this->assertTrue($baru->validate(), 'Validasi umum seharusnya lolos.');

        // Simulasi bentrok waktu seperti di controller
        $conflict = Jadwal::find()
            ->where([
                'tanggal_jadwal' => $baru->tanggal_jadwal,
                'laboratorium_id' => $baru->laboratorium_id,
                'flag' => 1
            ])
            ->andWhere([
                'or',
                ['between', 'waktu_mulai', $baru->waktu_mulai, $baru->waktu_selesai],
                ['between', 'waktu_selesai', $baru->waktu_mulai, $baru->waktu_selesai],
                ['and', ['<=', 'waktu_mulai', $baru->waktu_mulai], ['>=', 'waktu_selesai', $baru->waktu_selesai]]
            ])
            ->exists();

        $this->assertTrue($conflict, 'Jam bentrok tidak terdeteksi.');
    }
}
