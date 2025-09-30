<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pengerjaan;

/**
 * PengerjaanSearch represents the model behind the search form of `app\models\Pengerjaan`.
 */
class PengerjaanSearch extends Pengerjaan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'soal_id', 'mahasiswa_id', 'staff_check', 'flag'], 'integer'],
            [['kode_soal', 'waktu_pengumpulan', 'jawaban_teks', 'umpan_balik'], 'safe'],
            [['skor'], 'number'],
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
        $query = Pengerjaan::find();

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
            'soal_id' => $this->soal_id,
            'mahasiswa_id' => $this->mahasiswa_id,
            'waktu_pengumpulan' => $this->waktu_pengumpulan,
            'skor' => $this->skor,
            'staff_check' => $this->staff_check,
            'flag' => $this->flag,
        ]);

        $query->andFilterWhere(['like', 'kode_soal', $this->kode_soal])
            ->andFilterWhere(['like', 'jawaban_teks', $this->jawaban_teks])
            ->andFilterWhere(['like', 'umpan_balik', $this->umpan_balik]);

        return $dataProvider;
    }
}
