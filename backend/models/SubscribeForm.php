<?php

namespace backend\models;

use common\models\Author;
use common\models\Subscriber;
use common\models\Subscription;
use yii\base\Model;

class SubscribeForm extends Model
{
    public $phone;
    public $author_id;

    private ?Subscriber $subscriber = null;

    public function rules()
    {
        return [
            [['phone'], 'filter', 'filter' => 'trim'],
            [['author_id', 'phone'], 'required'],
            [['author_id'], 'number'],
            ['author_id', 'exist', 'targetClass' => Author::class, 'targetAttribute' => 'id'],
        ];
    }

    public function subscribe(): bool
    {
        if($this->validate()) {
           $this->subscriber = Subscriber::findOne(['phone' => $this->phone]);
           if(empty($this->subscriber)) {
               $this->subscriber = new Subscriber([
                   'phone' => $this->phone,
               ]);

               $this->subscriber->save();
           }

           $subscription = Subscription::findOne(['subscriber_id' => $this->subscriber->id, 'author_id' => $this->author_id]);

           if(empty($subscription)) {
               $subscription = new Subscription([
                   'subscriber_id' => $this->subscriber->id,
                   'author_id' => $this->author_id,
               ]);
               $subscription->save();
           }

            return true;
        }

        return false;
    }

    public function getSubscriber(): ?Subscriber
    {
        return $this->subscriber;
    }
}