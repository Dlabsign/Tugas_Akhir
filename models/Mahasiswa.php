<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mahasiswa".
 *
 * @property int $id
 * @property int $nim
 * @property int $semester
 * @property int $sesi_id
 * @property int $flag
 *
 * @property Jadwal $sesi
 */
class Mahasiswa extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'mahasiswa';
    }

    public function rules()
    {
        return [
            [['nim', 'semester', 'sesi_id', 'flag'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nim' => 'NIM',
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
}
