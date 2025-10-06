<?php

namespace backend\models;

use common\models\Book;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class BookSearch extends Model
{
    public $author;
    public $title;

    public function rules()
    {
        return [
            [['author', 'title'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = Book::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['title' => $this->title]);

        return $dataProvider;
    }
}