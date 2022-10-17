<?php

use yii\db\Migration;

/**
 * Class m200526_032443_create_hw_device_type
 */
class m200526_032443_create_hw_device_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('hw_device_type', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('Наименование'),
            'visible' => $this->smallInteger()->notNull()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('hw_device_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200526_032443_create_hw_device_type cannot be reverted.\n";

        return false;
    }
    */
}
