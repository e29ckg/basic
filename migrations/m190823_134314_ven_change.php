<?php

use yii\db\Migration;

/**
 * Class m190823_134314_ven_change
 */
class m190823_134314_ven_change extends Migration
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
 
        $this->createTable('ven_change', [
            'id' => $this->primaryKey(),
            'month' => $this->string(),
            'ven_id1' => $this->integer(),
            'ven_id2' => $this->integer(),
            'ven_id1_old' => $this->integer(),
            'ven_id2_old' => $this->integer(),
            'user_id1' => $this->integer(),
            'user_id2' => $this->integer(),
            's_po' => $this->integer(),
            's_bb' => $this->integer(),
            'file' => $this->string(),
            'status' => $this->string(),
            'ref1' => $this->string(),
            'ref2' => $this->string(),
            'comment' => $this->string(),
            'create_at' => $this->dateTime(),
        ], $tableOptions);
    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('ven_change');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190823_134314_ven_change cannot be reverted.\n";

        return false;
    }
    */
}
