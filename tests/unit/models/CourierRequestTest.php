<?php

declare(strict_types=1);

namespace tests\unit\models;

use app\models\Courier;
use app\models\CourierRequest;
use app\models\Vehicle;
use Yii;
use Codeception\Test\Unit;

class CourierRequestTest extends Unit
{
    private \yii\db\Transaction $transaction;
    private Courier $courier;
    private Vehicle $vehicle;

    protected function _before()
    {
        $this->transaction = Yii::$app->db->beginTransaction();

        // создаём тестового курьера и транспорт
        $this->courier = new Courier([
                                         'role' => Courier::ROLE_MAIN,
                                         'email' => 'request@example.com',
                                         'first_name' => 'Request',
                                         'last_name' => 'Tester',
                                     ]);
        $this->courier->save(false);

        $this->vehicle = new Vehicle([
                                         'courier_id' => $this->courier->id,
                                         'type' => Vehicle::TYPE_CAR,
                                         'serial_number' => 'REQ-1234',
                                     ]);
        $this->vehicle->save(false);
    }

    protected function _after()
    {
        $this->transaction->rollBack();
    }

    public function testValidationFailsWithoutStatus()
    {
        $request = new CourierRequest([
                                          'courier_id' => $this->courier->id,
                                          'vehicle_id' => $this->vehicle->id,
                                      ]);

        $this->assertFalse($request->validate(), 'Должна быть ошибка без статуса');
        $this->assertArrayHasKey('status', $request->errors);
    }

    public function testValidationPassesWithValidData()
    {
        $request = new CourierRequest([
                                          'courier_id' => $this->courier->id,
                                          'vehicle_id' => $this->vehicle->id,
                                          'status' => CourierRequest::STATUS_STARTED,
                                      ]);

        $this->assertTrue($request->validate(), 'Модель должна пройти валидацию с корректными данными');
    }

    public function testOnlyOneStartedRequestAllowed()
    {
        // первая "started" заявка
        $first = new CourierRequest([
                                        'courier_id' => $this->courier->id,
                                        'vehicle_id' => $this->vehicle->id,
                                        'status' => CourierRequest::STATUS_STARTED,
                                    ]);
        $first->save(false);

        // вторая — не должна пройти валидацию
        $second = new CourierRequest([
                                         'courier_id' => $this->courier->id,
                                         'vehicle_id' => $this->vehicle->id,
                                         'status' => CourierRequest::STATUS_STARTED,
                                     ]);
        $this->assertFalse($second->validate(), 'Должна быть ошибка, если уже есть активная заявка');
        $this->assertArrayHasKey('status', $second->errors);
    }

    public function testRelationsWork()
    {
        $request = new CourierRequest([
                                          'courier_id' => $this->courier->id,
                                          'vehicle_id' => $this->vehicle->id,
                                          'status' => CourierRequest::STATUS_HOLDED,
                                      ]);
        $request->save(false);

        $this->assertEquals($this->courier->id, $request->courier->id);
        $this->assertEquals($this->vehicle->id, $request->vehicle->id);
    }
}
