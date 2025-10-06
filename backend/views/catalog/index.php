<?php

use common\models\Book;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var \backend\models\BookSearch $searchModel */

$this->title = 'Catalog';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'year',
            [
                    'attribute' => 'cover',
                    'value' => fn (Book $book) => $book->cover ? Html::img("/$book->cover", ['alt' => $book->cover, 'height' => 100]) : '-',
                    'format' => 'raw',
            ],
            'isbn',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Book $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                'template' => '{view}',
            ],
        ],
    ]); ?>


</div>
