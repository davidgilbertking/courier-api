<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vehicles".
 *
 * @property int              $id
 * @property int              $courier_id
 * @property string           $type
 * @property string           $serial_number
 *
 * @property Courier          $courier
 * @property CourierRequest[] $courierRequests
 */
class Vehicle extends \yii\db\ActiveRecord
{
    /**
     * ENUM field values
     */
    const TYPE_CAR = 'car';

    const TYPE_SCOOTER = 'scooter';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vehicles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['courier_id', 'type', 'serial_number'], 'required'],
            [['courier_id'], 'integer'],
            [['type'], 'string'],
            [['serial_number'], 'string', 'max' => 255],
            ['type', 'in', 'range' => array_keys(self::optsType())],
            [['serial_number'], 'unique'],
            [['type'], 'validateUniqueTypePerCourier'],
            [
                ['courier_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Courier::class,
                'targetAttribute' => ['courier_id' => 'id'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'courier_id'    => 'Courier ID',
            'type'          => 'Vehicle Type',
            'serial_number' => 'Serial Number',
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
     * Gets query for [[CourierRequests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourierRequests()
    {
        return $this->hasMany(CourierRequest::class, ['vehicle_id' => 'id']);
    }

    /**
     * column type ENUM value labels
     *
     * @return string[]
     */
    public static function optsType()
    {
        return [
            self::TYPE_CAR     => 'car',
            self::TYPE_SCOOTER => 'scooter',
        ];
    }

    /**
     * @return string
     */
    public function displayType()
    {
        return self::optsType()[$this->type];
    }

    /**
     * @return bool
     */
    public function isTypeCar()
    {
        return $this->type === self::TYPE_CAR;
    }

    public function setTypeToCar()
    {
        $this->type = self::TYPE_CAR;
    }

    /**
     * @return bool
     */
    public function isTypeScooter()
    {
        return $this->type === self::TYPE_SCOOTER;
    }

    public function setTypeToScooter()
    {
        $this->type = self::TYPE_SCOOTER;
    }

    public function validateUniqueTypePerCourier($attribute, $params)
    {
        if (!$this->courier_id || !$this->type) {
            return;
        }

        $query = self::find()->where([
                                         'courier_id' => $this->courier_id,
                                         'type'       => $this->type,
                                     ]);

        // Исключаем текущую запись, если это обновление
        if (!$this->isNewRecord) {
            $query->andWhere(['<>', 'id', $this->id]);
        }

        if ($query->exists()) {
            $this->addError($attribute, 'У курьера уже есть транспорт этого типа.');
        }
    }

    public function extraFields()
    {
        return ['courier'];
    }
}
