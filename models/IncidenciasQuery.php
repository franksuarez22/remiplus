<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Incidencias]].
 *
 * @see Incidencias
 */
class IncidenciasQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Incidencias[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Incidencias|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
