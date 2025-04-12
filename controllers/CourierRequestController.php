<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use app\models\CourierRequest;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use app\components\ApiAuth;

class CourierRequestController extends ActiveController
{
    public $modelClass = 'app\models\CourierRequest';

    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = function ($action) {
            $query = CourierRequest::find()->where(['deleted' => false]);

            $courierId = Yii::$app->request->get('courier_id');
            if (!empty($courierId)) {
                $query->andWhere(['courier_id' => $courierId]);
            }

            $query->orderBy(['created_at' => SORT_DESC]);

            return new ActiveDataProvider([
                                              'query' => $query,
                                              'pagination' => ['pageSize' => 20],
                                          ]);
        };

        // Убираем дефолтное поведение create
        unset($actions['create']);

        return $actions;
    }

    public function actionCreate()
    {
        $model = new CourierRequest();
        $model->load(Yii::$app->getRequest()->getBodyParams());

        if (!$model->validate()) {
            Yii::$app->getResponse()->setStatusCode(422, 'Data Validation Failed.');
            Yii::$app->getResponse()->format = \yii\web\Response::FORMAT_JSON;
            return ['status' => 'error', 'errors' => $model->getErrors()];
        }

        if (!$model->save()) {
            Yii::$app->getResponse()->setStatusCode(500, 'Failed to save request.');
            return ['status' => 'error', 'message' => 'Не удалось сохранить заявку.'];
        }

        Yii::$app->getResponse()->setStatusCode(201);
        return $model;
    }

    /**
     * Переопределяем удаление: soft delete
     */
    public function actionDelete($id)
    {
        $model = CourierRequest::findOne(['id' => $id, 'deleted' => false]);
        if (!$model) {
            return [
                'status'  => 'error',
                'message' => 'Заявка не найдена или уже удалена.',
            ];
        }

        $model->deleted = true;
        $model->save(false);

        return ['status' => 'success', 'message' => 'Заявка помечена как удалённая.'];
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
