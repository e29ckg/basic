<?php

use yii\db\Migration;

/**
 * Class m190228_090317_log
 */
class m190228_090317_log extends Migration
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
 
        $this->createTable('log', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'manager' => $this->string(),
            'detail' => $this->string(),
            'ip' => $this->string(),
            'create_at' => $this->dateTime(),
        ], $tableOptions);

        $this->insert('log', [
            'id' => 1,
            'user_id' => 1,
            'manager' => 'Frist',
            'detail' => 'Detail',
            'ip' => 'IP',
            'create_at' => date("Y-m-d H:i:s"),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190228_090317_log cannot be reverted.\n";

        return false;
    }
    */
}
