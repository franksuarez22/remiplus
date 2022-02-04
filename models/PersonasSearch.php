<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Personas;

/**
 * PersonasSearch represents the model behind the search form about `app\models\Personas`.
 */
class PersonasSearch extends Personas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_persona', 'id_genero', 'id_nacionalidad', 'id_estado', 'id_municipio', 'id_parroquia', 'id_ciudad', 'cedula', 'usuario_creador', 'usuario_modificador'], 'integer'],
            [['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'fecha_nacimiento', 'telefono_contacto', 'correo_electronico', 'ip_log', 'fecha_creacion', 'fecha_modificacion'], 'safe'],
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
        $query = Personas::find();

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
            'id_persona' => $this->id_persona,
            'id_genero' => $this->id_genero,
            'id_nacionalidad' => $this->id_nacionalidad,
            'id_estado' => $this->id_estado,
            'id_municipio' => $this->id_municipio,
            'id_parroquia' => $this->id_parroquia,
            'id_ciudad' => $this->id_ciudad,
            'cedula' => $this->cedula,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'usuario_creador' => $this->usuario_creador,
            'usuario_modificador' => $this->usuario_modificador,
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_modificacion' => $this->fecha_modificacion,
            'estatus' => true,
        ]);

        $query->andFilterWhere(['like', 'primer_nombre', $this->primer_nombre])
            ->andFilterWhere(['like', 'segundo_nombre', $this->segundo_nombre])
            ->andFilterWhere(['like', 'primer_apellido', $this->primer_apellido])
            ->andFilterWhere(['like', 'segundo_apellido', $this->segundo_apellido])
            ->andFilterWhere(['like', 'telefono_contacto', $this->telefono_contacto])
            ->andFilterWhere(['like', 'correo_electronico', $this->correo_electronico])
            ->andFilterWhere(['like', 'ip_log', $this->ip_log]);

        return $dataProvider;
    }
}
