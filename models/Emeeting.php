<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "emeeting".
 *
 * @property int $id
 * @property string $name
 * @property string $date_create
 */
class Emeeting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'emeeting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title','start','end', 'cname', 'fname','tel'], 'required'],
            [['title', 'cname', 'fname'], 'string', 'max' => 255],
            [['file'], 'file', 'extensions' => 'pdf, png, jpg, jpeg', 'skipOnEmpty' => true, 'maxSize'=> 1024 * 1024 * 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'เรื่อง',
            'start' => 'เริ่ม',
            'end' => 'สิ้นสุด',
            'cname' => 'ชื่อศาล',
            'fname' => 'ขื่อผู้ขอ',
            'file' => 'หนังสือแนบ',
            'section' => 'กลุ่มงาน',
            'tel' => 'เบอร์โทรศัพท์',
            'detail' => 'รายละเอียด',
        ];
    }
}
