<?php

use common\models\Author;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Author $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="author-view">

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
            'first_name',
            'last_name',
            'surname',
            [
                    'attribute' => 'created_at',
                    'value' => fn (Author $author) => $author->created_at->format('Y-m-d H:i:s'),
            ],
            [
                    'attribute' => 'created_by',
                    'value' => fn (Author $author) => $author->creator?->username ?? 'System',
            ],
            [
                    'attribute' => 'updated_at',
                    'value' => fn (Author $author) => $author->updated_at->format('Y-m-d H:i:s'),
            ],
            [
                    'attribute' => 'updated_by',
                    'value' => fn (Author $author) => $author->updater?->username ?? 'System',
            ],
        ],
    ]) ?>

</div>
