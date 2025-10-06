<?php
namespace common\components\notifier\gateway\sms;

use common\components\notifier\BooksNotifyGateway;
use common\models\Subscriber;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class SmsGateway extends Component implements BooksNotifyGateway
{
    private SmsProvider $provider;

    public function __construct($config = [])
    {
        if (isset($config['provider'])) {
            $this->provider = Yii::createObject($config['provider']);
            unset($config['provider']);
        } else {
            throw new \InvalidArgumentException('SmsGateway requires "provider" config.');
        }

        parent::__construct($config);
    }


    public function notify(Subscriber $subscriber, array $books): bool
    {
        $text = "По вашим подпискам появились книги:\n" . implode("\n", ArrayHelper::getColumn($books, 'title'));

        return $this->provider->send($subscriber->phone, $text);
    }
}