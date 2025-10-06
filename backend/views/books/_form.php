<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var \backend\models\BookForm $model */
/** @var \common\models\Book $book */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cover')->fileInput() ?>

    <?php if ($book->cover):?>
        <img src="/<?=$book->cover?>" style="width: 200px" alt="book_cover">
    <?php endif;?>

    <?= $form->field($model, 'authors')->widget(Select2::class, [
            'options' => [
                'placeholder' => 'Select authors...',
                'multiple' => true,
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 2,
                'ajax' => [
                    'url' => Url::to(['/authors/list']),
                    'dataType' => 'json',
                    'delay' => 250,
                    'data' => new JsExpression('function(params) { 
                        return {q:params.term}; 
                    }'),
                    'processResults' => new JsExpression('function(data) {
                        return {results:data};
                    }'),
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(author) { return author.text; }'),
                'templateSelection' => new JsExpression('function (author) { return author.text; }'),
            ],
            'data' => $model->getAuthorTexts(),
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
