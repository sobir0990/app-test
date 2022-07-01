<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%send_email}}`.
 */
class m220701_063959_create_send_email_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%send_email}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'code' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createIndex(
            'idx-send_email-user_id',
            'send_email',
            'user_id'
        );

        $this->addForeignKey(
            'fk-send_email-user_id-user-id',
            'send_email',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%send_email}}');
    }
}
