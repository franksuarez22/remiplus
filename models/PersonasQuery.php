<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Personas]].
 *
 * @see Personas
 */
class PersonasQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Personas[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Personas|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
