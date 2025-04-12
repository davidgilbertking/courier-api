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

        $this->courier = new Courier([
                                         'role' => Courier::ROLE_MAIN,
                                         'email' => uniqid('request-') . '@example.com',
                                         'first_name' => 'Request',
                                         'last_name' => 'Tester',
                                     ]);
        $this->courier->save(false);

        $this->vehicle = new Vehicle([
                                         'courier_id' => $this->courier->id,
                                         'type' => Vehicle::TYPE_CAR,
                                         'serial_number' => uniqid('REQ-'),
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

        $this->assertFalse($request->validate());
        $this->assertArrayHasKey('status', $request->errors);
    }

    public function testValidationPassesWithValidData()
    {
        $request = new CourierRequest([
                                          'courier_id' => $this->courier->id,
                                          'vehicle_id' => $this->vehicle->id,
                                          'status' => CourierRequest::STATUS_STARTED,
                                      ]);

        $this->assertTrue($request->validate());
    }

    public function testOnlyOneStartedRequestAllowed()
    {
        $first = new CourierRequest([
                                        'courier_id' => $this->courier->id,
                                        'vehicle_id' => $this->vehicle->id,
                                        'status' => CourierRequest::STATUS_STARTED,
                                    ]);
        $first->save(false);

        $second = new CourierRequest([
                                         'courier_id' => $this->courier->id,
                                         'vehicle_id' => $this->vehicle->id,
                                         'status' => CourierRequest::STATUS_STARTED,
                                     ]);

        $this->assertFalse($second->validate());
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

    public function testStatusHelpersAndSetters()
    {
        $request = new CourierRequest([
                                          'status' => CourierRequest::STATUS_HOLDED
                                      ]);

        $this->assertTrue($request->isStatusHolded());
        $this->assertFalse($request->isStatusStarted());
        $this->assertFalse($request->isStatusFinished());

        $request->setStatusToStarted();
        $this->assertTrue($request->isStatusStarted());

        $request->setStatusToFinished();
        $this->assertTrue($request->isStatusFinished());
    }

    public function testDisplayStatus()
    {
        $request = new CourierRequest(['status' => CourierRequest::STATUS_HOLDED]);
        $this->assertEquals('holded', $request->displayStatus());

        $request->status = 'unexpected';
        $this->assertEquals('unexpected', $request->displayStatus(), 'Должен возвращаться оригинальный статус, если он не найден в optsStatus');
    }

    public function testExtraFields()
    {
        $request = new CourierRequest();
        $extra = $request->extraFields();

        $this->assertContains('courier', $extra);
        $this->assertContains('vehicle', $extra);
    }
}
