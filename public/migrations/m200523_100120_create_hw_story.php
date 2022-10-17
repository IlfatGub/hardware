<?php

use yii\db\Migration;

/**
 * Class m200522_104828_create_hw_tehnic
 */
class m200523_100120_create_hw_story extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //Создаем таблицу
        $this->createTable('hw_story', [
            'id' => $this->primaryKey(),
            'id_tehnic' => $this->integer()->notNull()->comment('Устройство'),
            'id_user' => $this->integer()->null()->comment('Пользватель'),
            'id_editor' => $this->integer()->notNull()->comment('Редактор'),
            'id_depart' => $this->integer()->null()->comment('отдел'),
            'id_podr' => $this->integer()->null()->comment('подразделение'),
            'id_org' => $this->integer()->null()->comment('организация'),
            'date' => $this->integer()->notNull()->comment('Дата'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('hw_story');
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
