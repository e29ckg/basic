<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/**
 * This is the model class for table "bila".
 *
 * @property int $id
 * @property int $user_id
 * @property string $cat
 * @property string $date_begin
 * @property string $date_end
 * @property string $date_total
 * @property string $dateO_begin
 * @property string $dateO_end
 * @property string $dateO_total
 * @property string $address
 * @property string $t1
 * @property string $t2
 * @property string $t3
 * @property string $po
 * @property string $bigboss
 * @property string $date_create
 */
class BilaFileUp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bila';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['file'], 'required'],
            [['file'], 'file', 'extensions' => 'pdf, png, jpg', 'skipOnEmpty' => true, 'maxSize'=> 1024 * 1024 * 5],
            // [['cat', 'date_begin', 'comment','date_end', 'dateO_begin', 'dateO_end', 'due', 'address', 't2', 'po', 'bigboss', 'date_create'], 'string', 'max' => 255],
        ];
    }


}
