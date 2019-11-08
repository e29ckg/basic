<?php

use yii\db\Migration;

/**
 * Class m190226_131745_ppss
 */
class m190226_131745_ppss extends Migration
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
 
        $this->createTable('ppss', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'black' => $this->string(),
            'red' => $this->string(),
            'persecutor' => $this->string(),
            'accused' => $this->string(),
            'page' => $this->integer(5),
            'note' => $this->text(),
            'file' => $this->string(),
            'file2' => $this->string(),
            'create_at' => $this->dateTime(),
            'create_own' => $this->string(),
        ], $tableOptions);

        $this->insert('ppss', [
            'id' => '1',
            'name' => 'test',
            'black' => 'Black',
            'red' => 'Red',
            'persecutor' => 'persecutor',
            'create_at' => date("Y-m-d H:i:s"),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('ppss');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190226_131745_ppss cannot be reverted.\n";

        return false;
    }
    */
}
