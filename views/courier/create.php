<?php

declare(strict_types=1);

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Courier $model */

$this->title = 'Create Courier';
$this->params['breadcrumbs'][] = ['label' => 'Couriers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courier-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
