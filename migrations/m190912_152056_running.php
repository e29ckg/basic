<?php

use yii\db\Migration;

/**
 * Class m190912_152056_running
 */
class m190912_152056_running extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
 
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
 
        $this->createTable('running', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'y' => $this->integer(),
            'r' => $this->integer(),
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('running');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190912_152056_running cannot be reverted.\n";

        return false;
    }
    */
}
