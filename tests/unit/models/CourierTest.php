<?php

declare(strict_types=1);

namespace tests\unit\models;

use app\models\Courier;
use Yii;
use Codeception\Test\Unit;

class CourierTest extends Unit
{
    private \yii\db\Transaction $transaction;

    protected function _before()
    {
        $this->transaction = Yii::$app->db->beginTransaction();
    }

    protected function _after()
    {
        $this->transaction->rollBack();
    }

    public function testValidationFailsWithEmptyFields()
    {
        $courier = new Courier();

        $this->assertFalse($courier->validate(), 'Модель не должна быть валидна без обязательных полей');
        $this->assertArrayHasKey('role', $courier->errors);
        $this->assertArrayHasKey('email', $courier->errors);
        $this->assertArrayHasKey('first_name', $courier->errors);
        $this->assertArrayHasKey('last_name', $courier->errors);
    }

    public function testValidationPassesWithCorrectData()
    {
        $courier = new Courier([
                                   'role'       => Courier::ROLE_MAIN,
                                   'email'      => 'test@example.com',
                                   'first_name' => 'Test',
                                   'last_name'  => 'User',
                               ]);

        $this->assertTrue($courier->validate(), 'Модель должна быть валидна при корректных данных');
    }

    public function testApiTokenIsGeneratedOnSave()
    {
        $courier = new Courier([
                                   'role'       => Courier::ROLE_MAIN,
                                   'email'      => 'token-test@example.com',
                                   'first_name' => 'Token',
                                   'last_name'  => 'Tester',
                               ]);

        $this->assertTrue($courier->save(false), 'Сохранение должно пройти успешно');
        $this->assertNotEmpty($courier->api_token, 'API токен должен быть сгенерирован');
    }

    public function testEmailMustBeUnique()
    {
        $email = 'unique@example.com';

        $first = new Courier([
                                 'role'       => Courier::ROLE_MAIN,
                                 'email'      => $email,
                                 'first_name' => 'First',
                                 'last_name'  => 'Courier',
                             ]);
        $this->assertTrue($first->save(), 'Первый курьер должен сохраниться');

        $second = new Courier([
                                  'role'       => Courier::ROLE_BASIC,
                                  'email'      => $email,
                                  'first_name' => 'Second',
                                  'last_name'  => 'Courier',
                              ]);
        $this->assertFalse($second->save(), 'Второй курьер с таким же email не должен сохраниться');
        $this->assertArrayHasKey('email', $second->errors, 'Ожидается ошибка на поле email');
    }

    public function testApiTokenNotRegeneratedOnUpdate()
    {
        $courier = new Courier([
                                   'role'       => Courier::ROLE_MAIN,
                                   'email'      => 'update-token@example.com',
                                   'first_name' => 'Original',
                                   'last_name'  => 'User',
                               ]);
        $courier->save(false);
        $originalToken = $courier->api_token;

        $courier->first_name = 'Updated';
        $courier->save(false);

        $this->assertEquals($originalToken, $courier->api_token, 'API токен не должен измениться при обновлении');
    }

    public function testInvalidRoleFailsValidation()
    {
        $courier = new Courier([
                                   'role'       => 'invalid',
                                   'email'      => 'role-test@example.com',
                                   'first_name' => 'Enum',
                                   'last_name'  => 'Test',
                               ]);

        $this->assertFalse($courier->validate(), 'Неверная роль должна вызывать ошибку валидации');
        $this->assertArrayHasKey('role', $courier->errors);
    }
}
