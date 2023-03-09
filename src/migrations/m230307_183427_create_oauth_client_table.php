<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%oauth_client}}`.
 */
class m230307_183427_create_oauth_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%oauth_client}}', [
            'id' => $this->string(80)->append('PRIMARY KEY'),
            'name' => $this->string(255)->notNull()->unique(),
            'secret_hash' => $this->string(255)->notNull(),
            'access_token_user_id' => $this->integer()->null(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
        $this->addForeignKey('fk_oauth_client_user_0', '{{%oauth_client}}', 'access_token_user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%oauth_client}}');
    }
}
