<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%depart}}`.
 */
class m211112_061552_create_depart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('hw_depart', [
            'id' => $this->primaryKey(),
            'name' => $this->smallInteger()->notNull()->notNull()->comment('Наименование отдела'),
            'id_user' => $this->smallInteger()->notNull()->notNull()->comment('Пользщователь'),
            'visible' => $this->smallInteger()->notNull()->defaultValue(null)->comment('Видимость'),
            'type' => $this->smallInteger()->notNull()->defaultValue(null)->comment('Тип устройства'),
        ]);

//        echo shell_exec("php yii gii/model --tableName=hw_specification_device --modelClass=HwSpecifDevice --interactive=0 --overwrite=1 --ns=app\\models");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('hw_depart');
    }
}