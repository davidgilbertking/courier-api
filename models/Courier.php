<?php

declare(strict_types=1);

namespace app\models;

use Yii;

/**
 * This is the model class for table "couriers".
 *
 * @property int              $id
 * @property string           $role
 * @property string           $email
 * @property string           $first_name
 * @property string           $last_name
 * @property string|null      $patronymic
 * @property string           $api_token
 *
 * @property CourierRequest[] $courierRequests
 * @property Vehicle[]        $vehicles
 */
class Courier extends \yii\db\ActiveRecord
{
    /**
     * ENUM field values
     */
    const ROLE_MAIN = 'main';

    const ROLE_BASIC = 'basic';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'couriers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['patronymic'], 'default', 'value' => null],
            [['role', 'email', 'first_name', 'last_name'], 'required'],
            [['role'], 'string'],
            [['email', 'first_name', 'last_name', 'patronymic'], 'string', 'max' => 255],
            ['role', 'in', 'range' => array_keys(self::optsRole())],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'         => 'ID',
            'role'       => 'Role',
            'email'      => 'Email',
            'first_name' => 'First Name',
            'last_name'  => 'Last Name',
            'patronymic' => 'Patronymic',
            'api_token'  => 'API Token',
        ];
    }

    /**
     * Gets query for [[CourierRequests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourierRequests(): \yii\db\ActiveQuery
    {
        return $this->hasMany(CourierRequest::class, ['courier_id' => 'id']);
    }

    /**
     * Gets query for [[Vehicles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVehicles(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Vehicle::class, ['courier_id' => 'id']);
    }

    /**
     * column role ENUM value labels
     *
     * @return string[]
     */
    public static function optsRole(): array
    {
        return [
            self::ROLE_MAIN  => 'main',
            self::ROLE_BASIC => 'basic',
        ];
    }

    /**
     * @return string
     */
    public function displayRole(): string
    {
        return self::optsRole()[$this->role];
    }

    /**
     * @return bool
     */
    public function isRoleMain(): bool
    {
        return $this->role === self::ROLE_MAIN;
    }

    public function setRoleToMain(): void
    {
        $this->role = self::ROLE_MAIN;
    }

    /**
     * @return bool
     */
    public function isRoleBasic(): bool
    {
        return $this->role === self::ROLE_BASIC;
    }

    public function setRoleToBasic(): void
    {
        $this->role = self::ROLE_BASIC;
    }

    public function beforeSave($insert): bool
    {
        if ($insert && empty($this->api_token)) {
            $this->api_token = Yii::$app->security->generateRandomString(64);
        }

        return parent::beforeSave($insert);
    }

    public function extraFields(): array
    {
        return ['vehicles', 'courierRequests'];
    }
}
