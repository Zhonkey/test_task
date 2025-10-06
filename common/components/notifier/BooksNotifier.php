<?php
namespace common\components\notifier;

use common\jobs\MakeNotificationJob;
use common\models\Author;
use common\models\Book;
use common\models\Notification;
use common\models\Subscriber;
use common\models\Subscription;
use Yii;
use yii\helpers\ArrayHelper;

class BooksNotifier extends \yii\base\Component
{
    public BooksNotifyGateway $gateway;

    public function __construct($config = [])
    {
        if (isset($config['gateway'])) {
            $this->gateway = Yii::createObject($config['gateway']);
            unset($config['gateway']);
        } else {
            throw new \InvalidArgumentException('BooksNotifier requires "gateway" config.');
        }

        parent::__construct($config);
    }

    public function initNotifications(Book $book, Author $author)
    {
        $subscriptions = Subscription::find()
            ->andWhere(['author_id' => $author->id])
            ->andWhere([
                'NOT EXISTS',
                Notification::find()
                    ->andWhere(['book_id' => $book->id])
                    ->andWhere(['is_success' => true])
                    ->andWhere(Notification::tableName() .  '.subscription_id = ' . Subscription::tableName() .  '.id')
            ])
            ->all();

        foreach ($subscriptions as $subscription) {
            $notification = new Notification([
                'subscription_id' => $subscription->id,
                'book_id' => $book->id,
            ]);

            $notification->save();

            Yii::$app->queue->push(new MakeNotificationJob([
                'subscriberId' => $subscription->subscriber->id,
            ]));
        }
    }

    public function makeNotification(Subscriber $subscriber)
    {
        $notifications = $this->getActualNotifications($subscriber);

        $result = $this->gateway->notify($subscriber, ArrayHelper::getColumn($notifications, 'book'));

        foreach ($notifications as $notification) {
            $notification->is_success = intval($result);
            $notification->save();
        }
    }

    private function getActualNotifications(Subscriber $subscriber): array
    {
        return Notification::find()
            ->joinWith('subscription')
            ->joinWith('book')
            ->andWhere([Subscription::tableName() .  '.subscriber_id' => $subscriber->id])
            ->andWhere(['is_success' => null])
            ->all();
    }
}