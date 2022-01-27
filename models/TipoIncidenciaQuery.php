<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Tipoincidencia]].
 *
 * @see Tipoincidencia
 */
class TipoIncidenciaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Tipoincidencia[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Tipoincidencia|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
