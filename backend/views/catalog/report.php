<?php

use common\components\report\topYear\TopYearAuthor;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var integer $year */

$this->title = $year ? "Top authors by year $year" : 'Top authors by years';
if($year) {
    $this->params['breadcrumbs'][] = ['label' => 'Top authors by years', 'url' => ['/catalog/report']];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'year',
                'value' => fn (TopYearAuthor $author) => $year ? $author->year : Html::a($author->year, Url::to(['/catalog/report', 'year' => $author->year])),
                'format' => 'raw',
            ],
            'year',
            [
                'attribute' => 'author',
                'value' => fn (TopYearAuthor $author) => $author->author->getFullName(),
            ],
            'count'
        ],
    ]); ?>


</div>
