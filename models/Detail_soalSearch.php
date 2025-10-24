<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Detail_soal;

/**
 * SoalSearch represents the model behind the search form of `app\models\Soal`.
 */
class Detail_soalSearch extends Detail_soal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sesi_id', 'matakuliah_id', 'bobot_soal', 'flag'], 'integer'],
            [['kode_soal', 'teks_soal', 'type', 'bahasa', 'data'], 'safe'],
            [['skor_maks'], 'number'],
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
        $query = Detail_soal::find()->where(['flag' => 1]);

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
            'sesi_id' => $this->sesi_id,
            'matakuliah_id' => $this->matakuliah_id,
            'bobot_soal' => $this->bobot_soal,
            'skor_maks' => $this->skor_maks,
            'flag' => $this->flag,
        ]);

        $query->andFilterWhere(['like', 'kode_soal', $this->kode_soal])
            ->andFilterWhere(['like', 'teks_soal', $this->teks_soal])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'bahasa', $this->bahasa])
            ->andFilterWhere(['like', 'data', $this->data]);

        return $dataProvider;
    }
}
