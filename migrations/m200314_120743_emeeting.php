<?php

use yii\db\Migration;

/**
 * Class m200314_120743_emeeting
 */
class m200314_120743_emeeting extends Migration
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
 
        $this->createTable('emeeting', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'start' => $this->dateTime(),
            'end' => $this->dateTime(),
            'cname' => $this->string(),
            'fname' => $this->string(),
            'file' => $this->string(),
            'section' => $this->string(),
            'tel' => $this->string(),
            'detail' => $this->string(),
            'status' => $this->string(),
            'ip' => $this->string(),
            'created_at' => $this->dateTime(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200314_120743_emeeting cannot be reverted.\n";
        $this->dropTable('emeeting');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200314_120743_emeeting cannot be reverted.\n";

        return false;
    }
    */
}
