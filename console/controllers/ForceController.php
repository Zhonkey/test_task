<?php

namespace console\controllers;

use common\jobs\MakeNotificationJob;
use common\models\Notification;
use common\models\Subscriber;
use Yii;
use yii\console\Controller;

class ForceController extends Controller
{
    public function actionNotifications()
    {
        $subscribers = Subscriber::find()
            ->joinWith('notifications')
            ->andWhere([Notification::tableName() . '.is_success' => null])
            ->all();

        foreach ($subscribers as $subscriber) {
            Yii::$app->queue->push(new MakeNotificationJob([
                'subscriberId' => $subscriber->id,
            ]));
        }
    }
}