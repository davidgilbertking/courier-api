<?php

declare(strict_types=1);

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Courier $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="courier-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'role')->dropDownList([ 'main' => 'Main', 'basic' => 'Basic', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patronymic')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
