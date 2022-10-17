<?php

use yii\db\Migration;

/**
 * Class m200521_045039_create_hw_post
 */
class m200521_045039_create_hw_post extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('hw_post', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('Подразделение'),
            'visible' => $this->smallInteger()->notNull()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('hw_post');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200521_045039_create_hw_post cannot be reverted.\n";

        return false;
    }
    */
}
