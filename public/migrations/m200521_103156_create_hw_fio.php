<?php

use yii\db\Migration;

/**
 * Class m200521_103156_create_hw_fio
 */
class m200521_103156_create_hw_fio extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //Создаем таблицу
        $this->createTable('hw_fio', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->comment('ФИО'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('hw_fio');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200521_103156_create_hw_fio cannot be reverted.\n";

        return false;
    }
    */
}
