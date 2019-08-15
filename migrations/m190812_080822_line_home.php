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
            'api_url' => 'http://127.0.0.1/basic/web/line/line_index',
            'callback_url' => 'http://127.0.0.1/basic/web/line/callback'
            ]);
        $this->insert('line_home', [
            'id' => 2,
            'client_id' => 'xxxxxx',
            'client_secret' => 'xxxxxx',
            'name_ser' => 'webPK_user',
            'api_url' => 'http://127.0.0.1/basic/web/line/line_index',
            'callback_url' => 'http://127.0.0.1/basic/web/user/callback'
            ]);
            $this->insert('line_home', [
                'id' => 3,
                'client_id' => 'pkkjc.coj@gmail.com',
                'client_secret' => 'AIzaSyCtp0KVVxbk9VapZoU-X4J6uaulYafzMQw',
                'name_ser' => 'googleCalendar',
                'api_url' => '',
                'callback_url' => ''
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
