<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "web_link_file".
 *
 * @property int $id
 * @property int $web_link_id
 * @property string $name
 * @property string $type
 * @property string $file
 * @property int $sort
 */
class WebLinkFile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'web_link_file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['file'], 'required'],
            [['web_link_id', 'sort'], 'integer'],
            [['name', 'type', 'file', 'url'], 'string', 'max' => 255],
            [['url'],'url', 'defaultScheme' => 'http'],
            [['file'], 'file', 'extensions' => 'pdf, txt, zip, rar, doc, docx, png, jpg', 'maxSize'=> 1024 * 1024 * 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'web_link_id' => 'Web Link ID',
            'name' => 'Name',
            'url' => 'url',
            'type' => 'Type',
            'file' => 'File',
            'sort' => 'Sort',
        ];
    }

    public function getWebLink()
    {
        return $this->hasOne(WebLink::className(), ['id' => 'web_link_id']);
        // return $this->hasMany(WebLinkFile::className(), ['web_link_id' => 'id']);
    }
}
