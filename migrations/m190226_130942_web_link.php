<?php

use yii\db\Migration;

/**
 * Class m190226_130942_web_link
 */
class m190226_130942_web_link extends Migration
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
 
        $this->createTable('web_link', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'link' => $this->string(),
            'img' => $this->string(),
            'create_at' => $this->dateTime(),
            'update_at' => $this->dateTime(),
        ], $tableOptions);

        $this->insert('web_link', [
            'id' => '1',
            'name' => 'GooGle',
            'link' => 'https://www.google.co.th',
            'create_at' => date("Y-m-d H:i:s"),
            'update_at' => date("Y-m-d H:i:s"),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('web_link');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190226_130942_web_link cannot be reverted.\n";

        return false;
    }
    */
}
