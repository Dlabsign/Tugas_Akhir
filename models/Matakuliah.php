<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

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
    public static function tableName()
    {
        return 'matakuliah';
    }
    public function rules()
    {
        return [
            [['flag'], 'default', 'value' => null],
            [['nama', 'semester'], 'required'],
            [['semester', 'flag'], 'integer'],
            [['nama'], 'string', 'max' => 255],
            [['nama'], 'unique'],
            [['created_at'], 'safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'semester' => 'Semester',
            'created_at' => 'Created At',
            'flag' => 'Flag',
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false, // kalau tidak perlu updated_at
                'value' => new Expression('NOW()'),
            ],
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
