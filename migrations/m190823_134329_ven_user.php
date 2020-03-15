<?php

use yii\db\Migration;

/**
 * Class m190823_134329_ven_user
 */
class m190823_134329_ven_user extends Migration
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
 
        $this->createTable('ven_user', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'order' => $this->string(),
            'DN' => $this->integer(2),
            'price' => $this->string(),
            'comment' => $this->string(),
            'create_at' => $this->dateTime(),
        ], $tableOptions);
    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('ven_user');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190823_134329_ven_user cannot be reverted.\n";

        return false;
    }
    */
}
