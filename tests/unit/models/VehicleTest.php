<?php

declare(strict_types=1);

namespace tests\unit\models;

use app\models\Courier;
use app\models\Vehicle;
use Yii;
use Codeception\Test\Unit;

class VehicleTest extends Unit
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
        $vehicle = new Vehicle();

        $this->assertFalse($vehicle->validate(), 'Модель не должна быть валидна без обязательных полей');
        $this->assertArrayHasKey('courier_id', $vehicle->errors);
        $this->assertArrayHasKey('type', $vehicle->errors);
        $this->assertArrayHasKey('serial_number', $vehicle->errors);
    }

    public function testValidationFailsWithInvalidType()
    {
        $courier = $this->createCourier();

        $vehicle = new Vehicle([
                                   'courier_id' => $courier->id,
                                   'type' => 'bike', // недопустимое значение
                                   'serial_number' => 'SN12345'
                               ]);

        $this->assertFalse($vehicle->validate());
        $this->assertArrayHasKey('type', $vehicle->errors);
    }

    public function testValidationPassesWithCorrectData()
    {
        $courier = $this->createCourier();

        $vehicle = new Vehicle([
                                   'courier_id' => $courier->id,
                                   'type' => Vehicle::TYPE_CAR,
                                   'serial_number' => 'VALID123'
                               ]);

        $this->assertTrue($vehicle->validate());
    }

    public function testSerialNumberMustBeUnique()
    {
        $courier = $this->createCourier();

        $first = new Vehicle([
                                 'courier_id' => $courier->id,
                                 'type' => Vehicle::TYPE_SCOOTER,
                                 'serial_number' => 'UNIQUE123'
                             ]);
        $this->assertTrue($first->save());

        $second = new Vehicle([
                                  'courier_id' => $courier->id,
                                  'type' => Vehicle::TYPE_CAR,
                                  'serial_number' => 'UNIQUE123'
                              ]);
        $this->assertFalse($second->save());
        $this->assertArrayHasKey('serial_number', $second->errors);
    }

    public function testCourierRelation()
    {
        $courier = $this->createCourier();

        $vehicle = new Vehicle([
                                   'courier_id' => $courier->id,
                                   'type' => Vehicle::TYPE_CAR,
                                   'serial_number' => 'RELTEST123'
                               ]);
        $vehicle->save(false);

        $this->assertInstanceOf(Courier::class, $vehicle->courier);
        $this->assertEquals($courier->id, $vehicle->courier->id);
    }

    private function createCourier(): Courier
    {
        $courier = new Courier([
                                   'role' => Courier::ROLE_MAIN,
                                   'email' => uniqid('vehicle-test-') . '@example.com',
                                   'first_name' => 'Vehicle',
                                   'last_name' => 'Tester',
                               ]);
        $courier->save(false);
        return $courier;
    }
}
