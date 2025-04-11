<?php

namespace app\controllers;

use Yii;
use app\models\Vehicle;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use app\components\ApiAuth;

class VehicleController extends ActiveController
{
    public $modelClass = 'app\models\Vehicle';

    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = function ($action) {
            $query = Vehicle::find();

            $courierId = Yii::$app->request->get('courier_id');
            $type      = Yii::$app->request->get('type');

            if ($courierId) {
                $query->andWhere(['courier_id' => $courierId]);
            }

            if ($type) {
                $query->andWhere(['type' => $type]);
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
