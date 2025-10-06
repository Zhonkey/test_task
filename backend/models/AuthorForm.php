<?php

namespace backend\models;

use common\models\Author;
use yii\base\Model;

class AuthorForm extends Model
{
    public $first_name;
    public $last_name;
    public $surname;

    private Author $model;

    public function rules()
    {
        return [
            [['first_name', 'last_name', 'surname'], 'filter', 'filter' => 'trim'],
            [['first_name', 'last_name'], 'required'],
            [['first_name', 'last_name', 'surname'], 'string'],
            ['first_name', 'validateUnique'],
        ];
    }

    public function validateUnique($attribute, $params)
    {
        $existed = Author::find()
            ->andWhere(['first_name' => $this->first_name])
            ->andWhere(['last_name' => $this->last_name])
            ->andWhere(['surname' => $this->surname ?: null])
            ->andFilterWhere(['!=', 'id', $this->model->id])
            ->exists();

        if ($existed) {
            $this->addError('first_name', 'Author already exists!');
            $this->addError('last_name', 'Author already exists!');
            $this->addError('surname', 'Author already exists!');
        }
    }

    public static function buildFromModel(Author $author): self
    {
        $form = new self();
        $form->last_name = $author->last_name;
        $form->first_name = $author->first_name;
        $form->surname = $author->surname;

        $form->model = $author;

        return $form;
    }

    public function updateAndSave(): bool
    {
        if($this->validate()) {
            $this->model->first_name = $this->first_name;
            $this->model->last_name = $this->last_name;
            $this->model->surname = $this->surname;
            $this->model->save();

            return true;
        }

        return false;
    }
}