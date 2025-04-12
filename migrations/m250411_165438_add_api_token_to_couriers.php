<?php

declare(strict_types=1);

use yii\db\Migration;

class m250411_165438_add_api_token_to_couriers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%couriers}}', 'api_token', $this->string(64)->unique());

        $couriers = (new \yii\db\Query())->from('couriers')->all();
        foreach ($couriers as $courier) {
            $token = Yii::$app->security->generateRandomString(64);
            Yii::$app->db->createCommand()->update('couriers', ['api_token' => $token], ['id' => $courier['id']])->execute();
        }

        $this->alterColumn('{{%couriers}}', 'api_token', $this->string(64)->notNull()->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%couriers}}', 'api_token');
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250411_165438_add_api_token_to_couriers cannot be reverted.\n";

        return false;
    }
    */
}
