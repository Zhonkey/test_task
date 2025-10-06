<?php

namespace console\controllers;

use backend\models\UserForm;
use Yii;
use yii\console\Controller;

class UserController extends Controller
{
    public function actionCreate($username)
    {
        $form = new UserForm([
            'username' => $username,
            'password' => $username,
        ]);

        $user = $form->getUser();

        if($form->updateAndSave($user)) {
            echo "User $user->username has been created with id {$user->id}\n";
        }  else {
            echo "User create failed, errors: \n" . json_encode($form->getErrors(), JSON_PRETTY_PRINT) . "\n";
        }
    }

    public function actionTest()
    {
        Yii::$app->bookNotifier->initNotifications(null, null);
    }
}