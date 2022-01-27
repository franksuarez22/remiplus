<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Denuncias]].
 *
 * @see Denuncias
 */
class DenunciasQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Denuncias[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Denuncias|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
