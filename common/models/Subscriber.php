<?php

namespace common\models;

/**
 * This is the model class for table "subscriber".
 *
 * @property int $id
 * @property string|null $phone
 *
 * @property Subscription[] $subscriptions
 */
class Subscriber extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscriber';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['created_at', 'updated_at'], 'safe'],
            [['phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptions()
    {
        return $this->hasMany(Subscription::class, ['subscriber_id' => 'id']);
    }
}
