<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "matakuliah".
 *
 * @property int $id
 * @property string $nama
 * @property int $semester
 * @property int|null $flag
 */
class Matakuliah extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'matakuliah';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['flag'], 'default', 'value' => null],
            [['nama', 'semester'], 'required'],
            [['semester', 'flag'], 'integer'],
            [['nama'], 'string', 'max' => 255],
            [['nama'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'semester' => 'Semester',
            'flag' => 'Flag',
        ];
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
    public function delete()
    {
        $this->flag = 0;
        return $this->save(false, ['flag']);
    }
}
