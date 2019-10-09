<?php

use yii\db\Migration;

/**
 * Class m191009_144247_court_order_bigboss
 */
class m191009_144247_court_order_bigboss extends Migration
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
 
        $this->createTable('court_order_bigboss', [
            'id' => $this->primaryKey(),
            'year' => $this->string(),
            'num' => $this->string(),
            'date_write' => $this->string(),
            'name' => $this->string(),
            'file' => $this->string(),
            'owner' => $this->string(),
            'create_at' => $this->dateTime(),
        ], $tableOptions);

        $this->insert('court_order_bigboss', [
            'year' => date("Y"),
            'num' => '1',
            'name' => 'คำสั่งศาล',
            'create_at' => date("Y-m-d H:i:s"),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191009_144247_court_order_bigboss cannot be reverted.\n";
        $this->dropTable('court_order_bigboss');
        return false;
    }

    
}
