<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%couriers}}`.
 */
class m250411_151938_create_couriers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%couriers}}', [
            'id' => $this->primaryKey(),
            'role' => "ENUM('main', 'basic') NOT NULL",
            'email' => $this->string()->notNull()->unique(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'patronymic' => $this->string()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%couriers}}');
    }
}
