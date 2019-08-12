<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "line_home".
 *
 * @property int $id
 * @property string $client_id
 * @property string $client_secret
 * @property string $name_ser
 * @property string $api_url
 * @property string $callback_url
 */
class LineHome extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'line_home';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'client_secret', 'name_ser', 'api_url', 'callback_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'client_secret' => 'Client Secret',
            'name_ser' => 'Name Ser',
            'api_url' => 'Api Url',
            'callback_url' => 'Callback Url',
        ];
    }
}
