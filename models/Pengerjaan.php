<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pengerjaan".
 *
 * @property int $id
 * @property int $soal_id
 * @property string $kode_soal
 * @property int $mahasiswa_id
 * @property string $waktu_pengumpulan
 * @property string|null $jawaban_teks
 * @property float|null $skor
 * @property string|null $umpan_balik
 * @property int|null $staff_check
 * @property int|null $flag
 */
class Pengerjaan extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengerjaan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jawaban_teks', 'skor', 'umpan_balik', 'staff_check', 'flag'], 'default', 'value' => null],
            [['soal_id', 'kode_soal', 'mahasiswa_id'], 'required'],
            [['soal_id', 'mahasiswa_id', 'staff_check', 'flag'], 'integer'],
            [['waktu_pengumpulan'], 'safe'],
            [['jawaban_teks', 'umpan_balik'], 'string'],
            [['skor'], 'number'],
            [['kode_soal'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'soal_id' => 'Soal ID',
            'kode_soal' => 'Kode Soal',
            'mahasiswa_id' => 'Mahasiswa ID',
            'waktu_pengumpulan' => 'Waktu Pengumpulan',
            'jawaban_teks' => 'Jawaban Teks',
            'skor' => 'Skor',
            'umpan_balik' => 'Umpan Balik',
            'staff_check' => 'Staff Check',
            'flag' => 'Flag',
        ];
    }

    public function getMahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, ['id' => 'mahasiswa_id']);
    }
}
