<?php
namespace common\jobs;

use common\models\Subscriber;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class MakeNotificationJob extends BaseObject implements JobInterface
{
    public $subscriberId;

    public function execute($queue)
    {
        $subscriber = Subscriber::findOne($this->subscriberId);

        if(empty($subscriber)){
            return;
        }

        Yii::$app->bookNotifier->makeNotification($subscriber);
    }
}