<?php

namespace app\models;

use Yii;

class Mahasiswa extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'mahasiswa';
    }

    public function rules()
    {
        return [
            [['nim', 'semester', 'kode_soal', 'sesi_id', 'flag'], 'integer'],
            [['nilai_sikap', 'nilai_kedisiplinan', 'nilai_akhir'], 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nim' => 'NIM',
            'kode_soal' => 'Kode Soal',
            'semester' => 'Semester',
            'nilai_sikap' => 'Nilai Sikap',
            'nilai_kedisiplinan' => 'Nilai Disiplin',
            'semester' => 'Semester',
            'sesi_id' => 'Sesi',
            'flag' => 'Flag',
        ];
    }
    public function delete()
    {
        $this->flag = 0;
        return $this->save(false, ['flag']);
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->flag = 1;
            }
            return true;
        }
        return false;
    }
    public function getSesi()
    {
        return $this->hasOne(Jadwal::class, ['id' => 'sesi_id']);
    }
    public function getMatakuliah()
    {
        return $this->hasOne(Matakuliah::class, ['id' => 'matakuliah_id'])->via('sesi');
    }

    public function getPengerjaan()
    {
        return $this->hasOne(Pengerjaan::class, ['mahasiswa_id' => 'id']);
    }

    // public function getLaboratorium()
    // {
    //     return $this->hasOne(Laboratorium::class, ['id' => 'laboratorium_id']);
    // }
}
