<?php

use yii\db\Migration;

/**
 * Class m200521_045256_create_hw_users
 */
class m200521_045256_create_hw_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //Создаем таблицу
        $this->createTable('hw_users', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->comment('ФИО пользователя'),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'id_post' => $this->integer()->notNull()->comment('Должность'),
            'id_podr' => $this->integer()->null()->defaultValue(null)->comment('Упарвление'),
            'id_depart' => $this->integer()->notNull()->comment('Отдел'),
            'id_org' => $this->integer()->notNull()->comment('Органзация'),
            'comment' => $this->string()->null()->comment('Коментарий'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('hw_users');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200521_045256_create_hw_users cannot be reverted.\n";

        return false;
    }
    */
}
