<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "laboratorium".
 *
 * @property int $id
 * @property string $nama
 * @property string|null $ruang
 * @property int|null $flag
 */
class Laboratorium extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'laboratorium';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ruang', 'flag'], 'default', 'value' => null],
            [['nama'], 'required'],
            [['flag'], 'integer'],
            [['nama'], 'string', 'max' => 100],
            [['ruang'], 'string', 'max' => 255],
            [['nama'], 'unique'],
            // Kode Testing
            [['ruang'], 'unique', 'targetAttribute' => 'ruang', 'message' => 'Ruangan sudah dipakai.'],
            [['ruang'], 'required', 'message' => 'Nama ruangan tidak boleh kosong.'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'ruang' => 'Ruang',
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
                $this->flag = 1; // Set flag to 1 when creating a new record
            }
            return true;
        }
        return false;
    }
}
