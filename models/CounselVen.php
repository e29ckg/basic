<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "counsel_ven".
 *
 * @property int $id
 * @property string $ven_date
 * @property string $legal_c_id
 * @property string $comment
 * @property string $create_at
 */
class CounselVen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'counsel_ven';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ven_date'], 'required'],
            [['ven_date', 'create_at'], 'safe'],
            [['legal_c_id', 'comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ven_date' => 'Ven Date',
            'legal_c_id' => 'Legal C ID',
            'comment' => 'Comment',
            'create_at' => 'Create At',
        ];
    }
}
