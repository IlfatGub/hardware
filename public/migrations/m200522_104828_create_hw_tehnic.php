<?php

use yii\db\Migration;

/**
 * Class m200522_104828_create_hw_tehnic
 */
class m200522_104828_create_hw_tehnic extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //Создаем таблицу
        $this->createTable('hw_tehnic', [
            'id' => $this->primaryKey(),
            'id_wh' => $this->integer()->notNull()->comment('Должность'),
            'id_org' => $this->integer()->notNull()->comment('Должность'),
            'id_model' => $this->integer()->notNull()->comment('Модель техники'),
            'serial' => $this->integer()->notNull()->comment('Серийный номер'),
            'part' => $this->integer()->null()->comment('Партийный номер'),
            'date_ct' => $this->integer()->notNull()->comment('Дата внесения в Хардваре'),
            'date_upd' => $this->integer()->null()->comment('Дата последнего изменения'),
            'id_user' => $this->integer()->null()->comment('Пользователь'),
            'type' => $this->integer()->null()->comment('Тип'),
            'comment' => $this->integer()->null()->comment('Коментарий'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('hw_tehnic');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200522_104828_create_hw_tehnic cannot be reverted.\n";

        return false;
    }
    */
}
