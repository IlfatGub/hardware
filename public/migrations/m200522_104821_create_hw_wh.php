<?php

use yii\db\Migration;

/**
 * Class m200522_104821_create_hw_wh
 */
class m200522_104821_create_hw_wh extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('hw_wh', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('Склад'),
            'visible' => $this->smallInteger()->notNull()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('hw_wh');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200522_104821_create_hw_wh cannot be reverted.\n";

        return false;
    }
    */
}
