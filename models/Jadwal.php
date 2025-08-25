<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jadwal".
 *
 * @property int $id
 * @property int $matakuliah_id
 * @property int $jumlah_peserta
 * @property int $laboratorium_id
 * @property string $tanggal_jadwal
 * @property string $waktu_mulai
 * @property string $waktu_selesai
 * @property int $dibuat_oleh_staff_id
 * @property int|null $flag
 */
class Jadwal extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jadwal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['flag'], 'default', 'value' => null],
            [['jumlah_peserta', 'laboratorium_id', 'tanggal_jadwal', 'waktu_mulai', 'waktu_selesai', 'dibuat_oleh_staff_id'], 'required'],
            [['jumlah_peserta', 'laboratorium_id', 'dibuat_oleh_staff_id', 'flag', 'matakuliah_id'], 'integer'],
            [['tanggal_jadwal', 'waktu_mulai', 'waktu_selesai'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'matakuliah_id' => 'Matakuliah ID',
            'jumlah_peserta' => 'Jumlah Peserta',
            'laboratorium_id' => 'Laboratorium ID',
            'tanggal_jadwal' => 'Tanggal Jadwal',
            'waktu_mulai' => 'Waktu Mulai',
            'waktu_selesai' => 'Waktu Selesai',
            'dibuat_oleh_staff_id' => 'Dibuat Oleh Staff ID',
            'flag' => 'Flag',
        ];
    }

    public function delete()
    {
        $this->flag = 0;
        return $this->save(false, ['flag']);
    }

    public function getLaboratorium()
    {
        return $this->hasOne(Laboratorium::class, ['id' => 'laboratorium_id']);
    }

    public function getPengguna()
    {
        return $this->hasOne(Pengguna::class, ['id' => 'dibuat_oleh_staff_id']);
    }

    public function getMatakuliah()
    {
        return $this->hasOne(Matakuliah::class, ['id' => 'matakuliah_id']);
    }
}
