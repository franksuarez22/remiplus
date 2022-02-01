<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tipoincidencia;

/**
 * TipoincidenciaSearch represents the model behind the search form about `app\models\Tipoincidencia`.
 */
class TipoincidenciaSearch extends Tipoincidencia
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tipo_incidencia', 'id_categoria_incidencia', 'usuario_creador', 'usuario_modificador'], 'integer'],
            [['nombre_tipo_incidencia', 'ip_log', 'fecha_creacion', 'fecha_modificacion'], 'safe'],
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
        $query = Tipoincidencia::find();

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
            'id_tipo_incidencia' => $this->id_tipo_incidencia,
            'id_categoria_incidencia' => $this->id_categoria_incidencia,
            'usuario_creador' => $this->usuario_creador,
            'usuario_modificador' => $this->usuario_modificador,
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_modificacion' => $this->fecha_modificacion,
            'estatus' => true,
        ]);

        $query->andFilterWhere(['like', 'nombre_tipo_incidencia', $this->nombre_tipo_incidencia])
            ->andFilterWhere(['like', 'ip_log', $this->ip_log]);

        return $dataProvider;
    }
}
