<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Jadwal extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'jadwal';
    }

    public function rules()
    {
        return [
            [['flag'], 'default', 'value' => null],
            [['laboratorium_id', 'tanggal_jadwal', 'waktu_mulai', 'waktu_selesai', 'dibuat_oleh_staff_id','sesi'], 'required'],
            [['laboratorium_id', 'dibuat_oleh_staff_id', 'flag', 'sesi', 'matakuliah_id'], 'integer'],
            [['tanggal_jadwal', 'waktu_mulai', 'waktu_selesai', 'created_at'], 'safe'],
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
            'sesi' => 'Sesi',
            'laboratorium_id' => 'Laboratorium ID',
            'tanggal_jadwal' => 'Tanggal Jadwal',
            'waktu_mulai' => 'Waktu Mulai',
            'waktu_selesai' => 'Waktu Selesai',
            'dibuat_oleh_staff_id' => 'Dibuat Oleh Staff ID',
            'flag' => 'Flag',
            'created_at' => 'Created At',
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
                $this->flag = 1; // Set flag to 1 when creating a new record
            }
            return true;
        }
        return false;
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
        return $this->hasOne(Matakuliah::class, ['id' => 'matakuliah_id'])->where(['flag' => 1]);
    }
}
