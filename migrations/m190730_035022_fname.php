<?php

use yii\db\Migration;

/**
 * Class m190730_035022_fname
 */
class m190730_035022_fname extends Migration
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
 
        $this->createTable('fname', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'date_create' => $this->string(),
        ], $tableOptions);    

        $this->insert('fname', ['name' => 'นาย','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('fname', ['name' => 'นาง','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('fname', ['name' => 'นางสาว','date_create' => date("Y-m-d H:i:s")]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('fname');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190730_035022_fname cannot be reverted.\n";

        return false;
    }
    */
}
