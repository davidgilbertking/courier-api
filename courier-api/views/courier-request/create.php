<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CourierRequest $model */

$this->title = 'Create Courier Request';
$this->params['breadcrumbs'][] = ['label' => 'Courier Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courier-request-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
