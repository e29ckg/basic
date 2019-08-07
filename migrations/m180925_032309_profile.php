<?php

use yii\db\Migration;

/**
 * Class m180925_032309_profile
 */
class m180925_032309_profile extends Migration
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
 
        $this->createTable('profile', [
            'id' => $this->primaryKey(),
            'user_id' => $this->string()->unique(),            
            'id_card' => $this->string(),
            'fname' => $this->string(25),
            'name' => $this->string(),
            'sname' => $this->string(),
            'img' => $this->string(),
            'birthday' => $this->date(),
            'bloodtype'=> $this->string(),
            'dep' => $this->string(),            
            'address' => $this->string(),
            'phone' => $this->string(),            
            'status' => $this->smallInteger()->defaultValue(10),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'st' => $this->smallInteger(),
        ], $tableOptions);

        $this->insert('profile', [
            'user_id' => '1',
            'name' => 'Admin',
            'sname' => 'S-Admin',
            'img' => '',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        $this->insert('profile', [
            'user_id' => '2',
            'name' => 'demo',
            'img' => '',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('profile');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180925_032309_profile cannot be reverted.\n";

        return false;
    }
    */
}
