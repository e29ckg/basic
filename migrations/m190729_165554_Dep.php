<?php

use yii\db\Migration;

/**
 * Class m190729_165554_Dep
 */
class m190729_165554_Dep extends Migration
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
 
        $this->createTable('dep', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'date_create' => $this->string(),
        ], $tableOptions);
    
        $this->insert('dep', ['name' => 'พนักงานคอมพิวเตอร์','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('dep', ['name' => 'นักวิชาการคอมพิวเตอร์','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('dep', ['name' => 'เจ้าหน้าที่ศาลยุติธรรมปฏิบัติงาน','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('dep', ['name' => 'เจ้าหน้าที่ศาลยุติธรรมชำนาญงาน','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('dep', ['name' => 'นักจิตวิทยาปฏิบัติการ','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('dep', ['name' => 'พนักงานสถานที่','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('dep', ['name' => 'พนักงานขับรถยนต์','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('dep', ['name' => 'เจ้าหน้่าที่ศาลยุติธรรม','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('dep', ['name' => 'เจ้าพนักงานศาลยุติธรรมปฏิบัติการ','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('dep', ['name' => 'นิติกรชำนาญการ','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('dep', ['name' => 'เจ้าพนักงานศาลยุติธรรมชำนาญการ','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('dep', ['name' => 'นักวิชาการเงินและบัญชีปฏิบัติการ','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('dep', ['name' => 'เจ้าพนักงานศาลยุติธรรมชำนาญการพิเศษ','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('dep', ['name' => 'นิติกร','date_create' => date("Y-m-d H:i:s")]);
        $this->insert('dep', ['name' => 'ผู้อำนวยการฯ','date_create' => date("Y-m-d H:i:s")]);
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('dep');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190729_165554_Dep cannot be reverted.\n";

        return false;
    }
    */
}
