<?php
namespace common\components\notifier;

use common\models\Book;
use common\models\Subscriber;

interface BooksNotifyGateway
{
    /**
     * @param Subscriber $subscriber
     * @param array<Book> $books
     * @return bool
     */
    public function notify(Subscriber $subscriber, array $books): bool;
}