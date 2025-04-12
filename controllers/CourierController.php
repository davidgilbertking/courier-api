<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use app\models\Courier;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use app\components\ApiAuth;

class CourierController extends ActiveController
{
    public $modelClass = 'app\models\Courier';

    public function actions()
    {
        $actions = parent::actions();

        // Фильтрация по role
        $actions['index']['prepareDataProvider'] = function ($action) {
            $query = Courier::find();

            $role = Yii::$app->request->get('role');
            if (!empty($role)) {
                $query->andWhere(['role' => $role]);
            }

            return new ActiveDataProvider([
                                              'query'      => $query,
                                              'pagination' => ['pageSize' => 20],
                                          ]);
        };

        return $actions;
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'auth' => [
                'class' => ApiAuth::class,
            ],
        ]);
    }
}
