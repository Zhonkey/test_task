<?php

namespace backend\models;

use common\models\User;
use Yii;
use yii\base\Model;

class UserForm extends Model
{
    public $id;
    public $username;
    public $password;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['id', 'integer'],
            [
                ['username'], 'unique', 'targetClass' => User::class, 'filter' => function ($query) {
                    $query->andFilterWhere(['!=', 'id', $this->id]);
                },
            ],
        ];
    }

    public function getUser()
    {
        return User::findOne($this->id) ?? new User();
    }

    public function updateAndSave(User $user): bool
    {
        if($this->validate()) {
            $user->username = $this->username;
            $user->auth_key = Yii::$app->security->generateRandomString();
            $user->setPassword($this->password);
            $user->save();

            return true;
        }

        return false;
    }
}