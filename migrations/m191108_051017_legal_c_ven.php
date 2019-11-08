<?php

use yii\db\Migration;

/**
 * Class m191108_051017_counsel
 */
class m191108_051017_legal_c_ven extends Migration
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
 
        $this->createTable('legal_c_ven', [
            'id' => $this->primaryKey(),
            'ven_date' => $this->date()->notNull(),
            'legal_c_id' => $this->string(),
            'comment' => $this->string(),
            'create_at' => $this->dateTime(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191108_051017_counsel cannot be reverted.\n";
        $this->dropTable('legal_c_ven');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191108_051017_counsel cannot be reverted.\n";

        return false;
    }
    */
}
