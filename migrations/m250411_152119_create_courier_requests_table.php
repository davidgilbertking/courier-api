<?php

declare(strict_types=1);

declare(strict_types=1);

use yii\db\Migration;

/**
 * Handles the creation of table `{{%courier_requests}}`.
 */
class m250411_152119_create_courier_requests_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%courier_requests}}', [
            'id' => $this->primaryKey(),
            'courier_id' => $this->integer(),
            'vehicle_id' => $this->integer(),
            'status' => "ENUM('started', 'holded', 'finished') NOT NULL",
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk_requests_courier',
            '{{%courier_requests}}',
            'courier_id',
            '{{%couriers}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_requests_vehicle',
            '{{%courier_requests}}',
            'vehicle_id',
            '{{%vehicles}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%courier_requests}}');
    }
}
