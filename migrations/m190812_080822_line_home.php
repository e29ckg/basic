<?php

use yii\db\Migration;

/**
 * Class m190812_080822_line_home
 */
class m190812_080822_line_home extends Migration
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
 
        $this->createTable('line_home', [
            'id' => $this->primaryKey(),
            'client_id' => $this->string(),
            'client_secret' => $this->string(),
            'name_ser' => $this->string(),
            'api_url' => $this->string(),
            'callback_url' => $this->string(),
        ], $tableOptions);
    
        $this->insert('line_home', [
            'id' => 1,
            'client_id' => 'xxxx',
            'client_secret' => 'xxxx',
            'name_ser' => 'webApp',
            'api_url' => 'http://xxx',
            'callback_url' => 'http://xxx'
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('line_home');
         echo "m190812_080822_line_home cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190812_080822_line_home cannot be reverted.\n";

        return false;
    }
    */
}
