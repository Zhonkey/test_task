<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \backend\models\AuthorForm $model */
/** @var \common\models\Author $author */

$this->title = 'Update Author: ' . $author->id;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $author->id, 'url' => ['view', 'id' => $author->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="author-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
