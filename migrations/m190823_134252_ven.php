<?php

use yii\db\Migration;

/**
 * Class m190823_134252_ven
 */
class m190823_134252_ven extends Migration
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
 
        $this->createTable('ven', [
            'id' => $this->primaryKey(),
            'ven_date' => $this->date()->notNull(),
            'ven_time' => $this->string(),
            'ven_com_id' => $this->string(),
            'user_id' => $this->string(),
            'file' => $this->string(),
            'status' => $this->integer(),
            'comment' => $this->string(),
            'ref1' => $this->string(),
            'ref2' => $this->string(),
            'create_at' => $this->dateTime(),
        ], $tableOptions);
    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('ven');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190823_134252_ven cannot be reverted.\n";

        return false;
    }
    */
}
