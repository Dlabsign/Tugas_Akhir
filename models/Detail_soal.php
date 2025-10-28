<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "soal".
 *
 * @property int $id
 * @property int $sesi_id
 * @property int $matakuliah_id
 * @property int $soal_id
 * @property int $bobot_soal
 * @property string $kode_soal
 * @property string $teks_soal
 * @property float $skor_maks
 * @property int|null $flag
 * @property string|null $nama_file
 * @property string|null $data
 */
class Detail_soal extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'detail_soal';
    }
    public function rules()
    {
        return [
            [['flag', 'data'], 'default', 'value' => null],
            [['kode_soal', 'teks_soal', 'skor_maks', 'sesi_id', 'type'], 'required'],
            [['matakuliah_id', 'sesi_id',  'flag', 'bahasa'], 'integer'],
            [['teks_soal', 'data'], 'string'],
            // [['skor_maks'], 'number'],
            [['kode_soal'], 'string', 'max' => 50],
            // [['nama_file'], 'string', 'max' => 255],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'matakuliah_id' => 'Matakuliah ID',
            'sesi_id' => 'Sesi Id',
            'bobot_soal' => 'Bobot Soal',
            'kode_soal' => 'Kode Soal',
            'teks_soal' => 'Teks Soal',
            // 'skor_maks' => 'Skor Maks',
            'flag' => 'Flag',
            'type' => 'Type',
            'bahasa' => 'Bahasa Program',
            'data' => 'Data',
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

    public static function createMultiple($modelClass, $multipleModels = [])
    {
        $model    = new $modelClass;
        $formName = $model->formName();
        $post     = Yii::$app->request->post($formName);
        $models   = [];

        if (! empty($multipleModels)) {
            $keys = array_keys(\yii\helpers\ArrayHelper::map($multipleModels, 'id', 'id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item['id']) && !empty($item['id']) && isset($multipleModels[$item['id']])) {
                    $models[] = $multipleModels[$item['id']];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }

        return $models;
    }
}
