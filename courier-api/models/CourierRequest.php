<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "courier_requests".
 *
 * @property int         $id
 * @property int|null    $courier_id
 * @property int|null    $vehicle_id
 * @property string      $status
 * @property int         $deleted
 * @property string|null $created_at
 *
 * @property Courier     $courier
 * @property Vehicle     $vehicle
 */
class CourierRequest extends \yii\db\ActiveRecord
{
    /**
     * ENUM field values
     */
    const STATUS_STARTED = 'started';

    const STATUS_HOLDED = 'holded';

    const STATUS_FINISHED = 'finished';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'courier_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['courier_id', 'vehicle_id'], 'default', 'value' => null],
            [['deleted'], 'default', 'value' => 0],
            [['courier_id', 'vehicle_id', 'deleted'], 'integer'],
            [['status'], 'required'],
            [['status'], 'string'],
            [['created_at'], 'safe'],
            [['status'], 'validateOnlyOneStarted'],
            [
                ['courier_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Courier::class,
                'targetAttribute' => ['courier_id' => 'id'],
            ],
            [
                ['vehicle_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Vehicle::class,
                'targetAttribute' => ['vehicle_id' => 'id'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'courier_id' => 'Courier ID',
            'vehicle_id' => 'Vehicle ID',
            'status'     => 'Status',
            'deleted'    => 'Deleted',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Courier]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourier()
    {
        return $this->hasOne(Courier::class, ['id' => 'courier_id']);
    }

    /**
     * Gets query for [[Vehicle]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVehicle()
    {
        return $this->hasOne(Vehicle::class, ['id' => 'vehicle_id']);
    }

    /**
     * column status ENUM value labels
     *
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_STARTED  => 'started',
            self::STATUS_HOLDED   => 'holded',
            self::STATUS_FINISHED => 'finished',
        ];
    }

    /**
     * @return string
     */
    public function displayStatus()
    {
        return self::optsStatus()[$this->status] ?? $this->status;
    }

    /**
     * @return bool
     */
    public function isStatusStarted()
    {
        return $this->status === self::STATUS_STARTED;
    }

    public function setStatusToStarted()
    {
        $this->status = self::STATUS_STARTED;
    }

    /**
     * @return bool
     */
    public function isStatusHolded()
    {
        return $this->status === self::STATUS_HOLDED;
    }

    public function setStatusToHolded()
    {
        $this->status = self::STATUS_HOLDED;
    }

    /**
     * @return bool
     */
    public function isStatusFinished()
    {
        return $this->status === self::STATUS_FINISHED;
    }

    public function setStatusToFinished()
    {
        $this->status = self::STATUS_FINISHED;
    }

    public function validateOnlyOneStarted($attribute, $params)
    {
        if ($this->status === 'started') {
            $query = self::find()
                         ->where([
                                     'courier_id' => $this->courier_id,
                                     'status'     => 'started',
                                     'deleted'    => false,
                                 ]);

            // Если id уже есть, исключаем текущую запись из поиска
            if ($this->id !== null) {
                $query->andWhere(['<>', 'id', $this->id]);
            }

            $exists = $query->exists();
            if ($exists) {
                $this->addError($attribute, 'У курьера уже есть активная заявка со статусом "started".');
            }
        }
    }

    public function formName()
    {
        return '';
    }

    public function extraFields()
    {
        return ['courier', 'vehicle'];
    }
}
