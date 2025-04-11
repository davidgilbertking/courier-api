<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vehicles}}`.
 */
class m250411_152030_create_vehicles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vehicles}}', [
            'id' => $this->primaryKey(),
            'courier_id' => $this->integer()->notNull(),
            'type' => "ENUM('car', 'scooter') NOT NULL",
            'serial_number' => $this->string()->notNull()->unique(),
        ]);

        $this->addForeignKey(
            'fk_vehicles_courier',
            '{{%vehicles}}',
            'courier_id',
            '{{%couriers}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%vehicles}}');
    }
}
