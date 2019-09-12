<?php

use yii\db\Migration;

/**
 * Class m190423_040403_bila
 */
class m190423_040403_bila extends Migration
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
 
        $this->createTable('bila', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'running' => $this->string(),
            'cat' => $this->string(),
            'p1' => $this->string(),
            'p2' => $this->string(),
            'date_begin' => $this->string(),
            'date_end' => $this->string(),
            'date_total' => $this->string(),
            'due' => $this->string(),
            'dateO_begin' => $this->string(),
            'dateO_end' => $this->string(),
            'dateO_total' => $this->string(),
            'address' => $this->string(),
            't1' => $this->string(),
            't2' => $this->string(),
            't3' => $this->string(),
            'comment' => $this->string(),
            'file' => $this->string(),
            'po' => $this->string(),
            'bigboss' => $this->string(),
            'date_create' => $this->string(),
        ], $tableOptions);

        // $this->insert('bila', [
            
        // ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('bila');

        return false;
    }
}
