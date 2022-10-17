<?php

use yii\db\Migration;

/**
 * Class m200522_104857_create_hw_model
 */
class m200522_104857_create_hw_model extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('hw_model', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('Наименование техники'),
            'vendor' => $this->string(100)->notNull()->comment('Производитель'),
            'type' => $this->smallInteger()->notNull()->comment('тип техники'),
            'visible' => $this->smallInteger()->notNull()->comment('видимость'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('hw_model');
    }

}