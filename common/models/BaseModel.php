<?php

namespace common\models;

use common\behaviors\DateTimeAttributes;
use common\models\traits\NotSaveExceptionedModel;
use DateTime;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base model with softDelete, enum fields and save after failed exception
 *
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 */
class BaseModel extends \yii\db\ActiveRecord
{
    use NotSaveExceptionedModel;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new \DateTime(),
            ],
            [
                'class' => DateTimeAttributes::class,
                'fields' => ['created_at', 'updated_at'],
            ],
        ];
    }
}
