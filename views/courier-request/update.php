<?php

declare(strict_types=1);

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CourierRequest $model */

$this->title = 'Update Courier Request: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Courier Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="courier-request-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
