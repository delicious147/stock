<?php

namespace common\components;

use Yii;


class MyHelper
{
    public static function postRequest($url, $data, $header = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        if ($header) {
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        return $content = curl_exec($curl);
    }

    public static function getRequest($url, $header = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        if ($header) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($curl, CURLOPT_POST, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        return $content = curl_exec($curl);
    }

    public static function getDownload($data,$fileName,$savePath){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$data );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $file = curl_exec($ch);
        curl_close($ch);
        $filename = pathinfo($fileName, PATHINFO_BASENAME);
        $resource = fopen($savePath . $filename, 'a');
        $result = fwrite($resource, $file);
        fclose($resource);
        return $result;
    }


    public static function curl($url, $data=null, $header)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        if ($header) {
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_USERPWD, $header);
        }
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        return $content = curl_exec($curl);
    }



    public static function post($url, $jsonStr)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
            )
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public static function get($url){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }



    /**
     *  rsa 生成签名
     */
    public static function createRsaSign($data){
        ksort($data);
        $data = http_build_query($data);
        $data = urldecode($data);
        $projectName=strtolower(Yii::$app->params['projectName']);
        $sign=null;
        $rsa_private_key=Yii::getAlias('@app/config/').$projectName."/".YII_ENV."/rsa/private_key.pem";
        $rsa=file_get_contents($rsa_private_key);

        $d = openssl_sign($data, $sign, $rsa,'SHA256');
        if($d==true){
            $mysign = base64_encode($sign);
            return  $mysign;
        }
        return 'error';
    }


    public static function createRsaSignTest($data){
        ksort($data);
        $data = http_build_query($data);
        $data = urldecode($data);
        $projectName=strtolower('kamirupiah');
        $sign=null;
        $rsa_private_key=Yii::getAlias('@app/config/').$projectName."/".'prod'."/rsa/private_key.pem";
        $rsa=file_get_contents($rsa_private_key);
        $d = openssl_sign($data, $sign, $rsa,'SHA256');
        if($d==true){
            $mysign = base64_encode($sign);
            return  $mysign;
        }
        return 'error';
    }


    /**
     *  rsa 验证签名
     */
    public static function validateRsaSign($data,$sign){
        if(isset($data['data'])){
            unset($data['data']);
        }
        if(isset($data['sign'])){
            unset($data['sign']);
        }
        ksort($data);
        $data = http_build_query($data);
        $data = urldecode($data);
        $projectName=strtolower(Yii::$app->params['projectName']);
        $rsa_public_key="../config/".$projectName."/".YII_ENV."/rsa/onepay_public_key.pem";
        $pubKey=file_get_contents($rsa_public_key);
        $res = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($pubKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";
        $result = (bool)openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
        return $result;
    }

    /**
     *  毫秒时间戳
     */
    public static function msecTime(){
        list($msec, $sec) = explode(' ', microtime());
        $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectime;
    }

    //随机数
    public static function random($length=16){
        $str="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        str_shuffle($str);
        $str=substr(str_shuffle($str),13,$length);
        return $str;
    }

    public static function AES($data,$key){
        $iv= "zxcvbnmk09876543";
        $encrypted = openssl_encrypt($data, 'AES-128-CBC', $key,OPENSSL_RAW_DATA, $iv);
        $encrypted = bin2hex($encrypted);
        $encrypted = base64_encode($encrypted);
        $encrypted = urlencode($encrypted);
        return  $encrypted;
    }

    /**
     *  叮叮机器人消息通知
     */
    public static function dingTalkMessage($message,$access_token =''){

        $access_token=(!isset($access_token) || empty($access_token)) ? '6b6502d548e6477cc32bee2e1340eab02b16fbcd368c0ce77ba26481edfdcdc0' : $access_token;
        $project=Yii::$app->params['projectName'] ?? ' ';
        $message=$project.' '.YII_ENV.' '.$message;
        $webhook='https://oapi.dingtalk.com/robot/send?access_token='.$access_token;
        $data = array ('msgtype' => 'text','text' => array ('content' => $message));
        $data_string = json_encode($data);

        $result = self::request_by_curl($webhook, $data_string);
        return $result;

    }

    private  static function request_by_curl($remote_server, $post_string) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json;charset=utf-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 线下环境不用开启curl证书验证, 未调通情况可尝试添加该代码
        // curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }



    //发送邮件
    public static function sendFileEmail($content, $to_name,$title)
    {
        $message = Yii::$app->mailer->compose();
        $message->setTextBody($content);
        $message->setTo($to_name); //要发送给那个人的邮箱
        $message->setSubject($title);
        if ($message->send()) {
            return true;
        }
        return false;
    }

    //AES加密
    public static function encryptAES($value, $key, $iv){
        $value= json_encode($value);
        //16位随机数
        if(strlen($iv)!=16){
            return 'iv length must is only 16 bytes';
        }
        $data['iv']=base64_encode($iv);
        $data['value']=openssl_encrypt($value, 'AES-128-CBC',$key,0,base64_decode($data['iv']));
        $encrypt=base64_encode(json_encode($data));
        return $encrypt;
    }
    //AES解密
    public static function decryptAES($key,$encrypt)
    {
        $encrypt = json_decode(base64_decode($encrypt), true);
        //在http post/get 传输过程 ‘+’号被转译为空格
        $iv = base64_decode($encrypt['iv']);
        $iv = preg_replace('/ /', '+', $iv);
        $encryptedData = preg_replace('/ /', '+', $encrypt['value']);

        $decrypt = openssl_decrypt($encryptedData, 'AES-128-CBC', $key, 0, $iv);
        $value = json_decode($decrypt,true);
        if($value){
            return $value;
        }else{
            return null;
        }
    }

    public static function makeCsv($data, $filePath = '')
    {
        //文件存在则写入，文件不存在则创建
        $path = $filePath;
        $handle = fopen($path, 'w');
        //解决excel乱码问题
        fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
        if (count($data) > 0) {
            $title = [];
            foreach ($data[0] as $k => $v) {
                $title[] = $k;
            }
            fputcsv($handle, $title);
            foreach ($data as $k => $v) {
                fputcsv($handle, $v);
            }
            fclose($handle);
        } else {
            return;
        }
    }

    //记录app Action log
    public static function appActionLog($action,$customer_id,$param){
        $model=new TAppActionLog();
        $model->action=$action;
        $model->customer_id=$customer_id;
        $model->ip=Yii::$app->request->userIP;
        $model->latitude=isset($param['latitude'])?$param['latitude']:'';
        $model->longitude=isset($param['longitude'])?$param['longitude']:'';
        $model->create_time=date('Y-m-d H:i:s');
        $model->save(false);
        return true;
    }

//    去除多余空格
    public static function dealwithName($string){
        $name_list = explode(" ",$string);
        $ret = [];

        foreach ($name_list as $v){
            if($v!=""){
                $ret[]=trim($v);
            }
        }

        return  implode(" ",$ret);
    }

    /**
     * 参数 分子，分母     5,10
     * 返回百分比     50%
     */
    public static function makePercentage($numerator,$denominator){
        if(!$denominator){
            return '0%';
        }else{
            return (round($numerator/$denominator,4)*100).'%';
        }
    }

    /**
     * 参数 小数    0.5
     * 返回 百分比  50%
     */
    public static function makeBeautyPercentage($percentage){
        if(!$percentage){
            return '0%';
        }else{
            return (round($percentage,4)*100).'%';
        }
    }

    /**
     * @param $money_fen  分
     * @return float|string  元
     */
    public static function makeMoney($money_fen){
        if(!$money_fen){
            return '0';
        }else{
            return floor($money_fen/100);
        }
    }


}