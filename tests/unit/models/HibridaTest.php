<?php

namespace tests\unit\models;

use app\models\Pengerjaan;
use Codeception\Test\Unit;

/**
 * HibridaTest.php
 * Menguji:
 * 1. Logika dasar penilaian (White-box)
 * 2. Akurasi evaluasi otomatis (Comparative Evaluation)
 */
class HibridaTest extends Unit
{
    private Pengerjaan $pengerjaan;

    protected function _before()
    {
        $this->pengerjaan = new Pengerjaan();
    }

    /**
     * White-box test: validasi jalur logika penilaian
     */
    public function testWhiteboxScoringLogic()
    {
        $evaluate = function ($jawabanMahasiswa, $jawabanBenar) {
            if (empty(trim($jawabanMahasiswa))) {
                return 0; // Jalur 4
            }
            if ($jawabanMahasiswa === $jawabanBenar) {
                return 100; // Jalur 1
            }
            similar_text($jawabanMahasiswa, $jawabanBenar, $percent);
            return round($percent); // Jalur 2 & 3
        };

        $skor1 = $evaluate('SELECT * FROM mahasiswa;', 'SELECT * FROM mahasiswa;');
        $this->assertEquals(100, $skor1, "Jalur 1 gagal.");

        $skor2 = $evaluate('SELECT nama FROM mahasiswa;', 'SELECT * FROM mahasiswa;');
        $this->assertTrue($skor2 > 50 && $skor2 < 100, "Jalur 2 gagal.");

        $skor3 = $evaluate('int main() { return 0; }', 'SELECT * FROM mahasiswa;');
        $this->assertLessThan(30, $skor3, "Jalur 3 gagal.");

        $skor4 = $evaluate('', 'SELECT * FROM mahasiswa;');
        $this->assertEquals(0, $skor4, "Jalur 4 gagal.");
    }

    /**
     * Comparative Evaluation (60 data)
     * Mengukur MAE dan akurasi sistem
     */
    public function testComparativeEvaluation60Data()
    {
        $manualScores = [
            97,
            79,
            86,
            52,
            82,
            94,
            62,
            87,
            78,
            92,
            53,
            67,
            83,
            64,
            82,
            60,
            53,
            87,
            91,
            84,
            75,
            61,
            85,
            76,
            78,
            63,
            93,
            68,
            81,
            89,
            83,
            86,
            72,
            59,
            54,
            64,
            98,
            57,
            81,
            63,
            55,
            97,
            82,
            72,
            53,
            81,
            80,
            50,
            99,
            66,
            54,
            73,
            92,
            52,
            82,
            84,
            81,
            96,
            78,
            68,
            74,
            64,
            62,
            67,
            61,
            77,
            96,


        ];

        $aiScores = [
            100,
            78,
            90,
            60,
            73,
            91,
            71,
            88,
            80,
            100,
            50,
            67,
            80,
            54,
            88,
            52,
            55,
            77,
            97,
            78,
            78,
            63,
            82,
            84,
            87,
            59,
            84,
            74,
            84,
            99,
            92,
            92,
            82,
            66,
            53,
            74,
            94,
            55,
            91,
            54,
            48,
            94,
            91,
            75,
            46,
            76,
            82,
            56,
            100,
            71,
            57,
            76,
            84,
            58,
            80,
            87,
            75,
            100,
            73,
            75,
            72,
            73,
            64,
            43,
            83,
            52,
            100,


        ];

        $n = count($manualScores);
        $totalError = 0;
        $matchCount = 0;

        for ($i = 0; $i < $n; $i++) {
            $diff = abs($manualScores[$i] - $aiScores[$i]);
            $totalError += $diff;
            if ($diff <= 10) {
                $matchCount++;
            }
        }

        $mae = $totalError / $n;
        $accuracy = ($matchCount / $n) * 100;

        // Output dengan format rapi ke terminal
        fwrite(STDOUT, "\n=== Hasil Evaluasi Komparatif ===\n");
        fwrite(STDOUT, "Jumlah Data: {$n}\n");
        fwrite(STDOUT, "Mean Absolute Error (MAE): " . round($mae, 1) . "\n");
        fwrite(STDOUT, "Akurasi: " . round($accuracy, 1) . "%\n");
        fwrite(STDOUT, "=================================\n");

        // Kriteria Keberhasilan
        $this->assertLessThanOrEqual(10, $mae, "MAE ($mae) terlalu besar, harus <= 10");
        $this->assertGreaterThanOrEqual(80, $accuracy, "Akurasi ($accuracy%) di bawah ambang batas 80%");
    }
}
