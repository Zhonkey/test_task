<?php

namespace common\components\notifier\gateway\sms;

interface SmsProvider
{
    public function send($phone, $message):bool;
}