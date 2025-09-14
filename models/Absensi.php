<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "absensi".
 *
 * @property int $id
 * @property int $mahasiswa_id
 * @property int $tanggal
 * @property int $time
 * @property int $soal_id
 */
class Absensi extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'absensi';
    }
    public function rules()
    {
        return [
            [['id', 'mahasiswa_id', 'tanggal', 'time', 'soal_id'], 'required'],
            [['id', 'mahasiswa_id', 'tanggal', 'time', 'soal_id', 'sesi_id'], 'integer'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mahasiswa_id' => 'Mahasiswa ID',
            'sesi_id' => 'Sesi',
            'tanggal' => 'Tanggal',
            'time' => 'Time',
            'soal_id' => 'Soal ID',
        ];
    }
}
