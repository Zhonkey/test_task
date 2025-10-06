<?php

namespace backend\models;

use common\models\User;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        $user = $this->getUser();

        if(empty($user) || !$user->validatePassword($this->password)){
            $this->addError($attribute, 'Incorrect username or password.');
        }
    }

    public function getUser()
    {
        return User::findOne(['username' => $this->username]);
    }

    public function login(): bool
    {
        if($this->validate()){
            $user = $this->getUser();
            Yii::$app->user->login($user, 3600 * 24 * 30);

            return true;
        }

        return false;
    }
}