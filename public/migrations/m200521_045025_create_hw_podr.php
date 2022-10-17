<?php

use yii\db\Migration;

/**
 * Class m200521_045025_create_hw_podr
 */
class m200521_045025_create_hw_podr extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('hw_podr', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('Подразделение'),
            'parent_id' => $this->integer(25)->null()->comment('Родитель'),
            'type' => $this->smallInteger()->notNull()->defaultValue(1),
            'visible' => $this->smallInteger()->notNull()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('hw_podr');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200521_045025_create_hw_podr cannot be reverted.\n";

        return false;
    }
    */
}
