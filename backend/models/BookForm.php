<?php

namespace backend\models;

use common\models\Author;
use common\models\Book;
use common\models\BookAuthor;
use common\models\traits\NotSaveException;
use common\models\User;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class BookForm extends Model
{
    public $title;
    public $year;
    public $description;
    public $isbn;
    public ?UploadedFile $cover = null;
    public array $authors = [];

    private Book $model;

    public function rules()
    {
        return [
            [['title', 'year'], 'required'],
            [['year'], 'integer', 'min' => 1000, 'max' => date('Y')],
            [['title', 'description'], 'string'],
            [
                ['isbn'], 'unique', 'targetClass' => Book::class, 'filter' => function ($query) {
                    $query->andFilterWhere(['!=', 'id', $this->model->id]);
                },
            ],
            ['authors', 'each', 'rule' => ['integer']],
            ['authors', 'each', 'rule' => ['exist', 'targetClass' => Author::class, 'targetAttribute' => 'id']],
        ];
    }

    public static function buildFromModel(Book $model): self
    {
        $form = new self();
        $form->title = $model->title;
        $form->year = $model->year;
        $form->description = $model->description;
        $form->isbn = $model->isbn;
        $form->authors = ArrayHelper::getColumn($model->authors, 'id');

        $form->model = $model;

        return $form;
    }

    public function updateAndSave(): bool
    {
        if($this->validate()) {
            try {
                Yii::$app->db->transaction(function () {
                    $this->updateBook();
                    $this->updateAuthors();
                });
                return true;
            } catch (NotSaveException $e) {
                return false;
            }
        }

        return false;
    }

    public function getAuthorTexts(): array
    {
        $authors = Author::find()->andWhere(['id' => $this->authors])->all();

        return ArrayHelper::map($authors, 'id', fn(Author $author) => $author->getFullName());
    }

    private function updateBook()
    {
        $this->model->title = $this->title;
        $this->model->description = $this->description;
        $this->model->year = $this->year;
        $this->model->isbn = $this->isbn;
        $this->saveCover();

        $this->model->save();
    }

    private function updateAuthors()
    {
        $bookAuthors = ArrayHelper::index($this->model->bookAuthors, 'author_id');

        foreach ($this->authors as $authorId) {
            if (isset($bookAuthors[$authorId])) {
                unset($bookAuthors[$authorId]);
            } else {
                $bookAuthor = new BookAuthor([
                    'book_id' => $this->model->id,
                    'author_id' => $authorId,
                ]);
                $bookAuthor->save();
            }
        }

        foreach ($bookAuthors as $bookAuthor) {
            $bookAuthor->delete();
        }
    }

    private function saveCover()
    {
        if ($this->cover) {
            $coverFolder = Yii::getAlias('@uploads/covers');
            if (!file_exists($coverFolder)) {
                mkdir($coverFolder);
            }
            $fileName = "uploads/covers/" . uniqid() . "." . $this->cover->extension;
            if ($this->cover->saveAs("@$fileName")) {
                $this->model->cover = $fileName;
            } else {
                Yii::error("Cant save cover by path: $fileName");
            }
        }
    }
}