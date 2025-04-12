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

    public function actions(): array
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

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $request = Yii::$app->request;

        // Разрешаем POST /couriers без авторизации, если курьеров нет
        // это создано для тестирования приложения
        $isCreatingCourier = $request->method === 'POST' && $request->pathInfo === 'couriers';
        $noCouriersYet = Courier::find()->count() === 0;

        if (!$isCreatingCourier || !$noCouriersYet) {
            $behaviors['auth'] = [
                'class' => ApiAuth::class,
            ];
        }

        return $behaviors;
    }
}
