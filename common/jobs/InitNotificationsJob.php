<?php
namespace common\jobs;

use common\models\Book;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class InitNotificationsJob extends BaseObject implements JobInterface
{
    public $bookId;
    public $authorId;

    public function execute($queue)
    {
        $book = Book::findOne($this->bookId);
        if(empty($book)) {
            return;
        }

        $author = $book->getAuthors()->andWhere(['id' => $this->authorId])->one();
        if(empty($author)) {
            return;
        }

        Yii::$app->bookNotifier->initNotifications($book, $author);
    }
}