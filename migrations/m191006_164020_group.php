<?php

use yii\db\Migration;

/**
 * Class m191006_164020_group
 */
class m191006_164020_group extends Migration
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
 
        $this->createTable('group', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'date_create' => $this->string(),
        ], $tableOptions);
    
        $this->insert('group', ['name' => 'ผู้อำนวยการ','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('group', ['name' => 'กลุ่มช่วยอำนวยการ','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('group', ['name' => 'กลุ่มงานช่วยพิจารณาคดี','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('group', ['name' => 'กลุ่มงานคดี','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('group', ['name' => 'กลุ่มงานคลัง','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('group', ['name' => 'กลุ่มงานปริการประชาชนและประชาสัมพันธ์','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('group', ['name' => 'กลุ่มงานไกล่เกลี่ยและประนอมข้อพิพาท','date_create' => date("Y-m-d H:i:s")]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('group');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191006_164020_group cannot be reverted.\n";

        return false;
    }
    */
}
