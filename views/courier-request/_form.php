<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CourierRequest $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="courier-request-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'courier_id')->textInput() ?>

    <?= $form->field($model, 'vehicle_id')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([ 'started' => 'Started', 'holded' => 'Holded', 'finished' => 'Finished', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'deleted')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
