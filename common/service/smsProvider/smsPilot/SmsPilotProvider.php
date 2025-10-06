<?php

namespace common\service\smsProvider\smsPilot;

use common\components\notifier\gateway\sms\SmsProvider;
use yii\base\Component;

class SmsPilotProvider extends Component implements SmsProvider
{
    private SmsPilot $api;

    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->api = new SmsPilot();
    }

    public function send($phone, $message): bool
    {
        $result = $this->api->send($phone, $message);

        return !($result === false);
    }
}