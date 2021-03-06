<?php

use yii\db\Migration;

/**
 * Class m190807_143350_lineE
 */
class m190807_143350_lineE extends Migration
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
 
        $this->createTable('line', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'token' => $this->string(),
            'status' => $this->smallInteger()->defaultValue(1),
        ], $tableOptions);
    
        $this->insert('line', ['name' => 'admin','token' => null]);
        $this->insert('line', ['name' => 'LineGroup','token' => null]);
        $this->insert('line', ['name' => 'bila_admin','token' => null]);
        $this->insert('line', ['name' => 'ven','token' => null]);  
        $this->insert('line', ['name' => 'court_order','token' => null]); 
        $this->insert('line', ['name' => 'emeeting','token' => null]);  
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('line');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190807_143350_lineE cannot be reverted.\n";

        return false;
    }
    */
}
