<?php

namespace backend\models;

use common\models\Author;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class AuthorSearch extends Model
{
    public $first_name;
    public $last_name;
    public $surname;

    public function rules()
    {
        return [
            [['first_name', 'last_name', 'surname'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = Author::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'first_name', $this->first_name]);
        $query->andFilterWhere(['like', 'last_name', $this->last_name]);
        $query->andFilterWhere(['like', 'surname', $this->surname]);

        return $dataProvider;
    }
}