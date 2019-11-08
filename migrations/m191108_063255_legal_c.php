<?php

use yii\db\Migration;

/**
 * Class m191108_063255_legal_c
 */
class m191108_063255_legal_c extends Migration
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
 
        $this->createTable('legal_c', [
            'id' => $this->primaryKey(),      
            'id_card' => $this->string(),
            'fname' => $this->string(25),
            'name' => $this->string(),
            'sname' => $this->string(),
            'img' => $this->string(),            
            'address' => $this->string(),
            'phone' => $this->string(),            
            'status' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191108_063255_legal_c cannot be reverted.\n";
        $this->dropTable('legal_c');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191108_063255_legal_c cannot be reverted.\n";

        return false;
    }
    */
}
