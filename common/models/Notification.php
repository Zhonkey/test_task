<?php

namespace common\models;

/**
 * This is the model class for table "notification".
 *
 * @property int $id
 * @property int|null $subscription_id
 * @property int $is_success
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Subscription $subscription
 * @property Book $book
 */
class Notification extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subscription_id', 'book_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['subscription_id', 'book_id', 'is_success'], 'integer'],
            [['is_success'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['subscription_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subscription::class, 'targetAttribute' => ['subscription_id' => 'id']],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => ['book_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subscription_id' => 'Subscription ID',
            'book_id' => 'Book ID',
            'is_success' => 'Is Success',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Subscription]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscription()
    {
        return $this->hasOne(Subscription::class, ['id' => 'subscription_id']);
    }

    /**
     * Gets query for [[Book]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }
}
