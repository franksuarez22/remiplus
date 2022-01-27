<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Incidencias;

/**
 * IncidenciasSearch represents the model behind the search form about `app\models\Incidencias`.
 */
class IncidenciasSearch extends Incidencias
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_incidencia', 'id_tipo_incidencia', 'id_parroquia', 'id_ciudad', 'usuario_creador', 'usuario_modificador'], 'integer'],
            [['descripcion', 'direccion', 'punto_referencia', 'imagen', 'ip_log', 'fecha_creacion', 'fecha_modificacion'], 'safe'],
            [['latitud', 'longitud'], 'number'],
            [['estatus'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Incidencias::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_incidencia' => $this->id_incidencia,
            'id_tipo_incidencia' => $this->id_tipo_incidencia,
            'id_parroquia' => $this->id_parroquia,
            'id_ciudad' => $this->id_ciudad,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'usuario_creador' => $this->usuario_creador,
            'usuario_modificador' => $this->usuario_modificador,
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_modificacion' => $this->fecha_modificacion,
            'estatus' => $this->estatus,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'punto_referencia', $this->punto_referencia])
            ->andFilterWhere(['like', 'imagen', $this->imagen])
            ->andFilterWhere(['like', 'ip_log', $this->ip_log]);

        return $dataProvider;
    }
}
