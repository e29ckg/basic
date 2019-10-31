<?php

use yii\db\Migration;

/**
 * Class m191031_162750_blueshirt
 */
class m191031_162750_blueshirt extends Migration
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
 
        $this->createTable('blueshirt', [
            'id' => $this->primaryKey(),
            'user_id' => $this->string(),
            'user_id2' => $this->string(),
            'line_alert' => $this->date(),
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191031_162750_blueshirt cannot be reverted.\n";
        $this->dropTable('blueshirt');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191031_162750_blueshirt cannot be reverted.\n";

        return false;
    }
    */
}
