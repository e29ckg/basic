<?php

use yii\db\Migration;

/**
 * Class m190823_134321_ven_com
 */
class m190823_134321_ven_com extends Migration
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
 
        $this->createTable('ven_com', [
            'id' => $this->primaryKey(),
            'ven_com_num' => $this->string(),
            'ven_com_name' => $this->string(),
            'comment' => $this->string(),
            'file' => $this->string(),
            'ref' => $this->string(),
            'status' => $this->string(),
            'create_at' => $this->dateTime(),
        ], $tableOptions);
    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('ven_com');
        return false;
    }

      

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190823_134321_ven_com cannot be reverted.\n";

        return false;
    }
    */
}
