<?php
namespace common\components\report\topYear;

use common\models\Author;
use common\models\Book;
use common\models\BookAuthor;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class TopYearReport
{
    /**
     * @param $year
     * @return array<TopYearAuthor>
     */
    public function request($year): array
    {
        $reportData = [];

        $topYearAuthorsCount = Book::find()
            ->select(['author_id id', 'count(*) count'])
            ->joinWith('bookAuthors', false)
            ->andWhere(['year' => $year])
            ->groupBy('author_id')
            ->orderBy('count(*) DESC')
            ->limit(10)
            ->asArray()
            ->indexBy('id')
            ->all();

        $topAuthors = Author::find()
            ->andWhere(['id' => ArrayHelper::getColumn($topYearAuthorsCount, 'id')])
            ->indexBy('id')
            ->all();

        foreach ($topAuthors as $author) {
            $reportData[] = new TopYearAuthor([
                'author' => $author,
                'count' => $topYearAuthorsCount[$author->id]['count'],
                'year' => $year
            ]);
        }

        return $reportData;
    }

    /**
     * @return array<TopYearAuthor>
     */
    public function totalYearsTop(): array
    {
        $bookTN = Book::tableName();
        $bookAuthorTN = BookAuthor::tableName();

        $rankQuery = Book::find()
            ->select([
                "{$bookTN}.year",
                "{$bookAuthorTN}.author_id",
                "COUNT({$bookTN}.id) books_count",
                'top_number' => new Expression("ROW_NUMBER() OVER (PARTITION BY {$bookTN}.year ORDER BY COUNT({$bookTN}.id) DESC)")
            ])
            ->joinWith('bookAuthors', false)
            ->groupBy([
                "{$bookTN}.year",
                "{$bookAuthorTN}.author_id"]
            );

        $topOneByYear = (new \yii\db\Query())
            ->select([
                't.year year',
                't.author_id author_id',
                't.books_count count'
            ])
            ->from(['t' => $rankQuery])
            ->andWhere(['t.top_number' => 1])
            ->orderBy(['t.year' => SORT_ASC])
            ->indexBy('year')
            ->limit(10)
            ->all();

        $topAuthors = Author::find()
            ->andWhere(['id' => ArrayHelper::getColumn($topOneByYear, 'author_id')])
            ->indexBy('id')
            ->all();

        foreach ($topOneByYear as $topOneData) {
            $reportData[] = new TopYearAuthor([
                'author' => $topAuthors[$topOneData['author_id']],
                'count' => $topOneData['count'],
                'year' => $topOneData['year']
            ]);
        }

        return $reportData;
    }
}