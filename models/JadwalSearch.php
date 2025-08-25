<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Jadwal;

/**
 * JadwalSearch represents the model behind the search form of `app\models\Jadwal`.
 */
class JadwalSearch extends Jadwal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'matakuliah_id', 'jumlah_peserta', 'laboratorium_id', 'dibuat_oleh_staff_id', 'flag'], 'integer'],
            [['tanggal_jadwal', 'waktu_mulai', 'waktu_selesai'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Jadwal::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'matakuliah_id' => $this->matakuliah_id,
            'jumlah_peserta' => $this->jumlah_peserta,
            'laboratorium_id' => $this->laboratorium_id,
            'tanggal_jadwal' => $this->tanggal_jadwal,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_selesai' => $this->waktu_selesai,
            'dibuat_oleh_staff_id' => $this->dibuat_oleh_staff_id,
            'flag' => $this->flag,
        ]);

        return $dataProvider;
    }
}
