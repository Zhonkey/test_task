<?php

namespace common\models;

use yii\behaviors\BlameableBehavior;

/**
 * This is the base model with softDelete, enum fields and save after failed exception
 *
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User|null $creator
 * @property User|null $updater
 */
class BaseCreatorModel extends BaseModel
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => BlameableBehavior::class,
                'value' => fn ($event) => isset(\Yii::$app?->user) ? \Yii::$app?->user?->identity?->getId() : null,
            ],
        ]);
    }

    public function getCreator()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getUpdater()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }
}
