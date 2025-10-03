<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $username
 * @property int $password_hash
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['password_hash'], 'required'],
            [['password_hash'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['username'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password_hash' => 'Password Hash',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
