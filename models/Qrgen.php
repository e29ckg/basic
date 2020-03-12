<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\base\Model;
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
class Qrgen extends Model
{
    public $url;

    /**
     * {@inheritdoc}
     */
    // public static function tableName()
    // {
    //     return '{{%bila}}';
    // }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['url'],'url', 'defaultScheme' => 'http'],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'url' => 'กรอก Link ที่จะสร้าง QrCode'
        ];
    }

    public function shout_url()
    {
        $key = "";//ใส่ api
        $url = "https://medium.com/@nattaponsirikamonnet";
        $long_url = str_replace(" ","%20",$url);
        $domain_data["fullName"] = "rebrand.ly";
        $post_data["destination"] = $long_url;
        $post_data["domain"] = $domain_data;
        
        $ch = curl_init("https://api.rebrandly.com/v1/links");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
           "apikey: ".$key,
           "Content-Type: application/json"
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result, true);
        
        return $response["shortUrl"];
    }

    
}
