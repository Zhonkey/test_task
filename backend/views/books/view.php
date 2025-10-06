<?php

use common\models\Book;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Book $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'year',
            'description:ntext',
            'isbn',
            [
                    'attribute' => 'cover',
                    'value' => fn (Book $book) => $book->cover ? Html::img("/$book->cover", ['alt' => $book->cover, 'height' => 200]) : '-',
                    'format' => 'raw',
            ],
            [
                    'attribute' => 'created_at',
                    'value' => fn (Book $book) => $book->created_at->format('Y-m-d H:i:s'),
            ],
            [
                    'attribute' => 'created_by',
                    'value' => fn (Book $book) => $book->creator?->username ?? 'System',
            ],
            [
                    'attribute' => 'updated_at',
                    'value' => fn (Book $book) => $book->updated_at->format('Y-m-d H:i:s'),
            ],
            [
                    'attribute' => 'updated_by',
                    'value' => fn (Book $book) => $book->updater?->username ?? 'System',
            ],
        ],
    ]) ?>

    <h3>Authors</h3>

    <ul>
        <?php foreach ($model->authors as $author): ?>
            <li>
                <?=$author->getFullName()?>
            </li>
        <?php endforeach;?>
    </ul>
</div>
