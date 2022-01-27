<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Nacionalidad]].
 *
 * @see Nacionalidad
 */
class NacionalidadQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Nacionalidad[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Nacionalidad|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
