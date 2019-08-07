<?php

use yii\db\Migration;

/**
 * Class m190510_082920_web_link_file
 */
class m190510_082920_web_link_file extends Migration
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
 
        $this->createTable('web_link_file', [
            'id' => $this->primaryKey(),
            'web_link_id' => $this->integer(11)->notNull(),
            'name' => $this->string(),
            'url' => $this->string(),
            'type' => $this->string(),
            'file' => $this->string(),
            'sort' => $this->integer(),
        ], $tableOptions);
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('web_link_file');

        return false;
    }

 
}
