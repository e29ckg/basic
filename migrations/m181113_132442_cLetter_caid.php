<?php

use yii\db\Migration;

/**
 * Class m181113_132442_cLetter_caid
 */
class m181113_132442_cLetter_caid extends Migration
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
 
        $this->createTable('c_letter_caid', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'order' => $this->integer(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
        ], $tableOptions);

        $this->insert('c_letter_caid', [
            'name' => 'ชื่อประเภทเอกสาร',
            'order' => 1,
            'status' => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181113_132442_cLetter_caid cannot be reverted.\n";
        $this->dropTable('c_letter_caid');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181113_132442_cLetter_caid cannot be reverted.\n";

        return false;
    }
    */
}
