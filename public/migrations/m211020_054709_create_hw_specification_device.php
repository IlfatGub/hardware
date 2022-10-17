<?php

use yii\db\Migration;

/**
 * Class m211020_054709_create_hw_specification_device
 */
class m211020_054709_create_hw_specification_device extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('hw_specification_device', [
            'id' => $this->primaryKey(),
            'id_device' => $this->smallInteger()->notNull()->notNull()->comment('Тип устройства'),
            'specification' => $this->string(255)->notNull()->comment('Характеристика устройства'),
            'visible' => $this->smallInteger()->notNull()->defaultValue(null)->comment('Видимость'),
            'type' => $this->smallInteger()->notNull()->defaultValue(null)->comment('Тип устройства'),
        ]);

        echo shell_exec("php yii gii/model --tableName=hw_specification_device --modelClass=HwSpecifDevice --interactive=0 --overwrite=1 --ns=app\\models");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('hw_specification_device');
    }
}
