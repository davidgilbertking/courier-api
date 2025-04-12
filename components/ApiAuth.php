<?php

declare(strict_types=1);

namespace app\components;

use Yii;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;
use app\models\Courier;

class ApiAuth extends ActionFilter
{
    public function beforeAction(Action $action): bool
    {
        $method = Yii::$app->request->method;

        // Разрешаем GET-запросы без авторизации
        if ($method === 'GET') {
            return parent::beforeAction($action);
        }

        $apiKey = Yii::$app->request->headers->get('X-Api-Key');

        if (!$apiKey) {
            throw new UnauthorizedHttpException('API ключ отсутствует.');
        }

        $courier = Courier::findOne(['api_token' => $apiKey]);
        if (!$courier) {
            throw new UnauthorizedHttpException('Неверный API ключ.');
        }

        Yii::$app->params['currentUser'] = $courier;

        // Только main-роль может использовать POST / PUT / DELETE
        if (in_array($method, ['POST', 'PUT', 'DELETE']) && $courier->role !== Courier::ROLE_MAIN) {
            throw new ForbiddenHttpException('Недостаточно прав.');
        }

        return parent::beforeAction($action);
    }

    /**
     * Проверка прав доступа на методы изменения данных
     */
    protected function checkPermissions(Courier $courier): void
    {
        $method = Yii::$app->request->method;

        if (in_array($method, ['POST', 'PUT', 'DELETE'], true) && $courier->role !== Courier::ROLE_MAIN) {
            throw new ForbiddenHttpException('Недостаточно прав.');
        }
    }
}
