<?php

use yii\db\Migration;

/**
 * Class m211020_060612_create_hw_specification_model
 */
class m211020_060612_create_hw_specification_model extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('hw_specification_model', [
            'id' => $this->primaryKey(),
            'id_device' => $this->smallInteger()->notNull()->notNull()->comment('Тип устройства'),
            'id_model' => $this->smallInteger()->notNull()->notNull()->comment('Модель устройства'),
            'specification' => $this->string(255)->notNull()->comment('Характеристика модели'),
            'visible' => $this->smallInteger()->notNull()->defaultValue(null)->comment('Видимость'),
            'type' => $this->smallInteger()->notNull()->defaultValue(null)->comment('Тип устройства'),
            'id_tehnic' => $this->smallInteger()->notNull()->defaultValue(null)->comment('Техника'),
        ]);

//        echo shell_exec("php yii gii/model --tableName=hw_specification_device --modelClass=HwSpecifDevice --interactive=0 --overwrite=1 --ns=app\\models");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('hw_specification_model');
    }
}
