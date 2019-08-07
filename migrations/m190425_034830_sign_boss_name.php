<?php

use yii\db\Migration;

/**
 * Class m190425_034830_sign_boss_name
 */
class m190425_034830_sign_boss_name extends Migration
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
 
        $this->createTable('sign_boss_name', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'dep1' => $this->string(),
            'dep2' => $this->string(),
            'dep3' => $this->string(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'date_create' => $this->string(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('sign_boss_name');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190425_034830_sign_boss_name cannot be reverted.\n";

        return false;
    }
    */
}
