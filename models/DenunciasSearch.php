<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Denuncias;
use mdm\admin\models\User;
use app\components\ComplementFunctions as cf;

/**
 * DenunciasSearch represents the model behind the search form about `app\models\Denuncias`.
 */
class DenunciasSearch extends Denuncias
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_denuncia', 'id_tipo_incidencia', 'id_estado', 'id_municipio', 'id_parroquia', 'id_ciudad', 'usuario_creador', 'usuario_modificador', 'id_categoria_incidencia'], 'integer'],
            [['descripcion', 'direccion', 'punto_referencia', 'ip_log', 'fecha_creacion', 'fecha_modificacion'], 'safe'],
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
        $query = Denuncias::find();
        $rol = cf::rolUsuario(Yii::$app->user->getId());
        $usuario = User::findIdentity(Yii::$app->user->getId());
        $persona = Personas::findOne(['cedula'=>$usuario->cedula, 'estatus' => true]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if(!in_array('Administrador', $rol)){
            $this->id_estado=$persona->id_estado;
            $this->id_municipio=$persona->id_municipio;
            $this->id_parroquia=$persona->id_parroquia;
        }
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_denuncia' => $this->id_denuncia,
            'id_tipo_incidencia' => $this->id_tipo_incidencia,
            'id_categoria_incidencia' => $this->id_categoria_incidencia,
            'id_estado' => $this->id_estado,
            'id_municipio' => $this->id_municipio,
            'id_parroquia' => $this->id_parroquia,
            'id_ciudad' => $this->id_ciudad,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'usuario_creador' => $this->usuario_creador,
            'usuario_modificador' => $this->usuario_modificador,
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_modificacion' => $this->fecha_modificacion,
            'estatus' => true,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'punto_referencia', $this->punto_referencia])
            ->andFilterWhere(['like', 'ip_log', $this->ip_log]);

        return $dataProvider;
    }
}
