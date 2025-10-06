<?php

use backend\models\SubscribeForm;
use common\models\Book;
use common\models\Subscriber;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Book $model */
/** @var Subscriber|null $subscriber */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
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
        ],
    ]) ?>


    <h3>Authors</h3>

    <ul>
        <?php foreach ($model->authors as $author): ?>
            <li>
                <?=$author->getFullName()?>

                <?php if(empty($subscriber) || !$subscriber->isSubscribedTo($author)):?>
                    <?= Html::a('Subscribe', ['subscribe', 'id' => $model->id], ['data-bs-toggle' => "modal", 'data-bs-target' => "#subscribeModal{$author->id}"]) ?>

                    <?php $subscribeModel = new SubscribeForm([
                        'phone' => $subscriber?->phone ?? null,
                        'author_id' => $author->id,
                    ]);?>

                    <div id="subscribeModal<?=$author->id?>" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4>Subscribe</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="subscribeModalbtn-close"></button>
                                </div>
                                <div class="modal-body">
                                    <?php $form = ActiveForm::begin(['action' => '/catalog/subscribe']); ?>
                                        <div class="text-center">
                                            <div class="fs-15 mx-4 mx-sm-5">
                                                <p class="text-muted mx-4 mb-0">Please set phone for subscription.</p>
                                            </div>
                                        </div>
                                        <?=$form->field($subscribeModel, 'author_id')->hiddenInput()->label(false)?>
                                        <?=$form->field($subscribeModel, 'phone')->textInput()?>
                                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn w-sm btn-danger">Subscribe!</button>
                                        </div>
                                    <?php ActiveForm::end(); ?>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php else:;?>
                    <span class="text-success">Subscribed</span>
                <?php endif;?>
            </li>
        <?php endforeach;?>
    </ul>

</div>
