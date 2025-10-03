<?php

namespace common\models\traits;

use common\exceptions\NotSaveException;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;

trait SoftDeletableModel
{
    /**
     * @throws NotSaveException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function softDelete()
    {
        $this->deleted_at = date('Y-m-d H:i:s');
        $this->deleted_by = \Yii::$app->user?->getId();
        return $this->save();
    }

    public function isDeleted()
    {
        return isset($this->deleted_at);
    }

    public static function find(): ActiveQuery
    {
        return parent::find()->andWhere([static::tableName() . '.deleted_at' => null]);
    }

    public static function findIncludingDeleted(): ActiveQuery
    {
        return parent::find();
    }
}
