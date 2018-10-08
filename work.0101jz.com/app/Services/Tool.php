<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

/**
 * 通用工具服务类
 */
class Tool
{

    /**
     * HTTP Protocol defined status codes
     * HTTP协议状态码,调用函数时候只需要将$num赋予一个下表中的已知值就直接会返回状态了。
     * @param int $num
     *
     */
    public static function https($num) {
        $http = array (
            100 => "HTTP/1.1 100 Continue",
            101 => "HTTP/1.1 101 Switching Protocols",
            200 => "HTTP/1.1 200 OK",
            201 => "HTTP/1.1 201 Created",
            202 => "HTTP/1.1 202 Accepted",
            203 => "HTTP/1.1 203 Non-Authoritative Information",
            204 => "HTTP/1.1 204 No Content",
            205 => "HTTP/1.1 205 Reset Content",
            206 => "HTTP/1.1 206 Partial Content",
            300 => "HTTP/1.1 300 Multiple Choices",
            301 => "HTTP/1.1 301 Moved Permanently",
            302 => "HTTP/1.1 302 Found",
            303 => "HTTP/1.1 303 See Other",
            304 => "HTTP/1.1 304 Not Modified",
            305 => "HTTP/1.1 305 Use Proxy",
            307 => "HTTP/1.1 307 Temporary Redirect",
            400 => "HTTP/1.1 400 Bad Request",
            401 => "HTTP/1.1 401 Unauthorized",
            402 => "HTTP/1.1 402 Payment Required",
            403 => "HTTP/1.1 403 Forbidden",
            404 => "HTTP/1.1 404 Not Found",
            405 => "HTTP/1.1 405 Method Not Allowed",
            406 => "HTTP/1.1 406 Not Acceptable",
            407 => "HTTP/1.1 407 Proxy Authentication Required",
            408 => "HTTP/1.1 408 Request Time-out",
            409 => "HTTP/1.1 409 Conflict",
            410 => "HTTP/1.1 410 Gone",
            411 => "HTTP/1.1 411 Length Required",
            412 => "HTTP/1.1 412 Precondition Failed",
            413 => "HTTP/1.1 413 Request Entity Too Large",
            414 => "HTTP/1.1 414 Request-URI Too Large",
            415 => "HTTP/1.1 415 Unsupported Media Type",
            416 => "HTTP/1.1 416 Requested range not satisfiable",
            417 => "HTTP/1.1 417 Expectation Failed",
            500 => "HTTP/1.1 500 Internal Server Error",
            501 => "HTTP/1.1 501 Not Implemented",
            502 => "HTTP/1.1 502 Bad Gateway",
            503 => "HTTP/1.1 503 Service Unavailable",
            504 => "HTTP/1.1 504 Gateway Time-out"
        );
        header($http[$num]);
    }

    /**
     * 取得IP
     *
     *
     * @return string 字符串类型的返回结果
     */
    public static function getIp(){
        if (@$_SERVER['HTTP_CLIENT_IP'] && $_SERVER['HTTP_CLIENT_IP']!='unknown') {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (@$_SERVER['HTTP_X_FORWARDED_FOR'] && $_SERVER['HTTP_X_FORWARDED_FOR']!='unknown') {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return preg_match('/^\d[\d.]+\d$/', $ip) ? $ip : '';
    }


    /**
     * 获取文件列表(所有子目录文件)
     *
     * @param string $path 目录
     * @param array $file_list 存放所有子文件的数组
     * @param array $ignore_dir 需要忽略的目录或文件
     * @return array 数据格式的返回结果
     */
    public static function readFileList($path,&$file_list,$ignore_dir=array()){
        $path = rtrim($path,'/');
        if (is_dir($path)) {
            $handle = @opendir($path);
            if ($handle){
                while (false !== ($dir = readdir($handle))){
                    if ($dir != '.' && $dir != '..'){
                        if (!in_array($dir,$ignore_dir)){
                            if (is_file($path.DS.$dir)){
                                $file_list[] = $path.DS.$dir;
                            }elseif(is_dir($path.DS.$dir)){
                                self::readFileList($path.DS.$dir,$file_list,$ignore_dir);
                            }
                        }
                    }
                }
                @closedir($handle);
                //return $file_list;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    /**
     * 生成订单流水号（18位数字）
     * 最大可以支持1分钟1亿订单号不重复
     *
     * @return string $orderSn
     */
    public static function createSn($namespace = 'default', $prefix = '', $length = 8)
    {
        $insertId = Yii::$app->redis->incr('FlowSn:' . ucfirst($namespace));
        $suffix   = self::getSnSuffix();

        return $prefix . date('ymdHi') . str_pad(substr($insertId, -$length), $length, 0, STR_PAD_LEFT) . $suffix;
    }

    /**
     * 产生随机字符串
     *
     * @param int $length
     *
     * @return string
     */
    public static function createRandomStr($length = 32)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';

        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }

        return $str;
    }

    /**
     * 生成随机令牌凭证
     *
     * @return string
     */
    public static function buildToken($uniqueId = null)
    {
        return sha1(uniqid($uniqueId) . mt_rand(1, 10000));
    }

    /**
     * 订单号生成器
     * @param int $uid 用户id
     * @return int
     */
    function order_sn($uid)
    {
        return '619' . date('Ymd') . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT) . $uid;
    }

    /**
     * 获取唯一标识长度,最长37位,默认10位
     *
     * @param int $length 字符串长度
     *
     * @return string
     */
    public static function createUniqueNumber($length = 10)
    {
        return substr(date('YmdHis') . md5(uniqid()), 0, $length);
    }

    /**
     * 根据字符集生成随机字符串
     *
     * @param int $length 字符串长度
     * @param int $type 0:纯数字, 1:数字与字母
     *
     * @return string
     */
    public static function generatePassword($length = 6, $type = 0)
    {
        if ($type == 1) {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        }
        else {
            $chars = '0123456789';
        }
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[mt_rand(0, strlen($chars) - 1)];
        }

        return $password;
    }

    /**
     * Xml To array
     *
     * @param $data
     *
     * @return array
     */
    public static function xmlToArray($data)
    {
        // 禁止引用外部xml实体
        libxml_disable_entity_loader(true);

        $data = str_ireplace(['encoding="GB2312"', 'encoding="GBK"'], 'encoding="GB18030"', $data);

        // 先把xml转换为simplexml对象，再把simplexml对象转换成 json，再将 json 转换成数组
        try {
            $result = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);

            return $result ? json_decode(json_encode($result), true) : [];
        } catch (\Throwable $e) {
            throws('xml格式不正确：' . $data);
        }
    }

    /**
     * 数组转换成xml
     *
     * @param $arr
     * @param string $root
     * @param string $endroot
     *
     * @return string
     */
    public static function arrayToXml($arr, $root = '<msgdata>', $endroot = '</msgdata>')
    {
        $xml = $root;

        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                if (is_numeric($key)) {
                    $xml .= self::arrayToXml($val, '', '');
                } else {
                    $xml .= '<' . $key . '>' . self::arrayToXml($val, '', '') . '</' . $key . '>';
                }
            }
            else {
                if (is_numeric($val) || $val === '') {
                    $xml .= '<' . $key . '>' . $val . '</' . $key . '>';
                }
                else {
                    $xml .= '<' . $key . '><![CDATA[' . $val . ']]></' . $key . '>';
                }
            }
        }

        $xml .= $endroot;

        return $xml;
    }

    /**
     * 获取xml post请求数据
     *
     * @return bool|mixed
     */
    public static function getXmlPost()
    {
        if (version_compare(PHP_VERSION, '5.6.0', '<')) {
            if (! empty($GLOBALS['HTTP_RAW_POST_DATA'])) {
                $xmlInput = $GLOBALS['HTTP_RAW_POST_DATA'];
            }
            else {
                $xmlInput = file_get_contents('php://input');
            }
        }
        else {
            $xmlInput = file_get_contents('php://input');
        }

        if (empty($xmlInput)) {
            return [];
        }

        // 禁止引用外部xml实体
        libxml_disable_entity_loader(true);

        // 先把xml转换为simplexml对象，再把simplexml对象转换成 json，再将 json 转换成数组
        return json_decode(json_encode(simplexml_load_string($xmlInput, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 保存redis值-json/序列化保存
     * @param string 必填 $pre 前缀
     * @param string $key 键 null 自动生成
     * @param string 选填 $value 需要保存的值，如果是对象或数组，则序列化
     * @param int 选填 $expire 有效期 秒 <=0 长期有效
     * @param int 选填 $operate 操作 1 转为json 2 序列化
     * @return $key
     * @author zouyan(305463219@qq.com)
     */
    public static function setRedis($pre = '', $key = null, $value = '', $expire = 0, $operate = 1)
    {
        if(empty($key)){
            $key = self::createUniqueNumber(25);
        }
        $key = $pre . $key;
        // 序列化保存
        try{
            switch($operate){
                case 1:
                    if(is_array($value)){
                        $value = json_encode($value);
                    }
                    break;
                case 2:
                    $value = serialize($value);
                    break;
                default:
                    break;
            }
            if(is_numeric($expire) && $expire > 0){
                Redis::setex($key, $expire, $value);
            }else{
                Redis::set($key, $value);
            }
        } catch ( \Exception $e) {
            throws('redis[' . $key . ']保存失败；信息[' . $e->getMessage() . ']');
        }
        return $key;
    }

    /**
     * 获得key的redis值
     * @param string $key 键
     * @param int 选填 $operate 操作 1 转为json 2 序列化
     * @return $value ; false失败
     * @author zouyan(305463219@qq.com)
     */
    public static function getRedis($key, $operate = 1)
    {
        $value = Redis::get($key);
        if(is_bool($value) || is_null($value)){//string或BOOL 如果键不存在，则返回 FALSE。否则，返回指定键对应的value值。
            return false;
        }
        switch($operate){
            case 1:
                if (!self::isNotJson($value)) {
                    $value = json_decode($value, true);
                }
                break;
            case 2:
                $value = unserialize($value);
                break;
            default:
                break;
        }
        return $value;

    }
    /**
     * 获得key的redis值
     * @param string $key 键
     * @return $value
     * @author zouyan(305463219@qq.com)
     */
    public static function delRedis($key)
    {
        return Redis::del($key);
    }


    //判断数据不是JSON格式:
    public static function isNotJson($str){
        return is_null(json_decode($str));
    }

    // 保存session

    /**
     * 保存redis值-json/序列化保存
     * @param string 必填 $pre 前缀
     * @param string $key 键 null 自动生成
     * @param string 选填 $value 需要保存的值，如果是对象或数组，则序列化
     * @param int 选填 $expire 有效期 秒 <=0 长期有效
     * @param int 选填 $operate 操作 1 转为json 2 序列化
     * @return $key
     * @author zouyan(305463219@qq.com)
     */
    /**
     * 保存session值-json/序列化保存 注意如果是session，一定要确保前面有 session_start(); // 初始化session
     * @param string $key_pre 前缀
     * @param string 选填 $value 需要保存的值，如果是对象或数组，则序列化
     * @param boolean 选填 $save_session 是否保存session true:键保存到session.false，只返回key，给小程序用
     * @param string 选填 $session_key  如果保存的session，session的键名
     * @param int 选填 $expire 有效期 秒 <=0 长期有效 60*60*24*1
     * @param int 选填 $operate 操作 1 转为json 2 序列化
     * @return $redisKey  数据在redis中的键值
     * @author zouyan(305463219@qq.com)
     */
    public static function setLoginSession($key_pre= 'login', $value = '', $save_session = true, $session_key = 'loginKey', $expire = 0, $operate = 1)
    {
        $key = null;// 键名
        $pre = '';// 前缀
        $need_save_key = false; // 是否需要重新获得key
        if($save_session){
            if (!session_id()) session_start();
            $key = $_SESSION[$session_key] ?? '';
            if(empty($key)){
                $key = null;
                $need_save_key = true;
            }
        }
        // 没有key则加前缀
        if(empty($key)){
            $pre = $key_pre;
        }

        $redisKey = self::setRedis($pre, $key, $value, $expire , $operate); // 1天

        // key有变化
        if($save_session && $need_save_key){
            if (!session_id()) session_start();
            $_SESSION[$session_key] = $redisKey;
        }
        return $redisKey;
    }

    // 获得session

    /**
     * 获得key的值 注意如果是session，一定要确保前面有 session_start(); // 初始化session
     * @param string $redisKey 全键[含前缀],小程序传入的 $save_session 为 true时，可以传null
     * @param boolean 选填 $save_session 是否保存session true:键保存到session.false，只返回key，给小程序用
     * @param string 选填 $session_key  如果保存的session，session的键名
     * @param int 选填 $operate 操作 1 转为json 2 序列化
     * @return $value redis中保存的数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getSession($redisKey = null, $save_session = true, $session_key = 'loginKey', $operate = 1)
    {
        if($save_session){
            if (!session_id()) session_start();
            $redisKey = $_SESSION[$session_key] ?? '';
        }
        $val = '';
        if(!empty($redisKey)){
            $val = self::getRedis($redisKey, $operate);
        }else{
            // throws('参数redisKey不能为空!');
        }
        return $val;
    }
    /**
     * 获得key的值 注意如果是session，一定要确保前面有 session_start(); // 初始化session
     * @param string $redisKey 全键[含前缀],小程序传入的 $save_session 为 true时，可以传null
     * @param boolean 选填 $save_session 是否保存session true:键保存到session.false，只返回key，给小程序用
     * @param string 选填 $session_key  如果保存的session，session的键名
     * @return boolean true:成功 ;false:失败
     * @author zouyan(305463219@qq.com)
     */
    public static function delSession($redisKey = null, $save_session = true, $session_key = 'loginKey')
    {
        if($save_session){
            if (!session_id()) session_start();
            $redisKey = $_SESSION[$session_key] ?? '';
        }

        if($save_session && isset($_SESSION[$session_key])){
            unset($_SESSION[$session_key]); //保存某个session信息
        }
        return self::delRedis($redisKey); // 删除redis中的值
    }

    // 数组操作

    /**
     * 返回以原数组某个值为下标的新数组
     *
     * @param array $array
     * @param string $key
     * @param int $type 1一维数组2二维数组
     * @return array
     */
    public static function arrUnderReset($array, $key, $type=1){
        if (is_array($array)){
            $tmp = [];
            foreach ($array as $v) {
                if ($type === 1){
                    $tmp[$v[$key]] = $v;
                }elseif($type === 2){
                    $tmp[$v[$key]][] = $v;
                }
            }
            return $tmp;
        }else{
            return $array;
        }
    }


    /**
     * 二维数组指定下标的值为下标,指定下标的值为值，的一维数组
     *
     * @param array $array 二维数组
     * @param string $uboundkey 值做为新数组的键的下标
     * @param string $uboundValKey 值做为新数组的键的下标
     * @return array 一维数组
     */
    public static function formatArrKeyVal($array, $keyUbound, $valUbound){
        $reArr = [];
        if (! is_array($array)) return $reArr;
        foreach ($array as $v) {
            if( !isset($v[$keyUbound]) || !isset($v[$valUbound])) continue;
            $reArr[$v[$keyUbound]] = $v[$valUbound];
        };
        return $reArr;
    }

    /**
     * 一维数组返回指定下标数组的一维数组,-以原数组下标不准，
     *
     * @param array $array 一维数组
     * @param array $keys 要获取的下标数组 -维 [ '新下标名' => '原下标名' ]
     * @param boolean $needNotIn  keys在数组中不存在的，false:不要，true：空值
     * @return array 一维数组
     */
    public static function formatArrKeys(&$array, $keys, $needNotIn = false){
        foreach($array as $k => $v){
            if(!in_array($k , $keys )){
                unset($array[$k]);
                continue;
            }
        }
        if(! $needNotIn){
            foreach($keys as $new_k => $old_k ){
//                if(isset($array[$old_k])){
                    if($new_k != $old_k){
                        $array[$new_k] = $array[$old_k];
                        unset($array[$old_k]);
                    }
//                    continue;
//                }
            }
            return $array;
        }

        foreach($keys as $new_k => $old_k ){
            if(isset($array[$old_k])){
                if($new_k != $old_k){
                    $array[$new_k] = $array[$old_k];
                    unset($array[$old_k]);
                }
                continue;
            }
            $array[$new_k] = '';

        }
        return $array;
    }

    /**
     * 二维数组返回指定下标数组的新的二维维数组,-以原数组下标为准，
     *
     * @param array $array 二维数组
     * @param array $keys 要获取的下标数组 -维[ '新下标名' => '原下标名' ]
     * @param boolean $needNotIn  keys在数组中不存在的，false:不要，true：空值
     * @return array 一维数组
     */
    public static function formatTwoArrKeys(&$array, $keys, $needNotIn = false){
        foreach($array as $k => $v){
            self::formatArrKeys($array[$k], $keys, $needNotIn );
        }
        return $array;
    }

    /**
     * 一维数组转换为键值相同的一维数组
     *
     * @param array $array 一维数组
     * @param boolean $equalType  统计的类型，false:以键为标准，true：以值为标准
     * @return array 一维数组
     */
    public static function arrEqualKeyVal($array,  $equalType = true){
        $reArr = [];
        foreach($array as $k => $v){
            if($equalType){
                $reArr[$v] = $v;
            }else{
                $reArr[$k] = $k;
            }
        }
        return $reArr;
    }

    /**
     * 获得当前的路由和方法
     *
     * @return string 当前的路由和方法  App\Http\Controllers\CompanyWorkController@addInit
     */
    public static function getActionMethod(){
        return \Route::current()->getActionName();
    }

    /**
     * 获得缓存数据
     * @param string $pre 键前缀 __FUNCTION__
     * @param string $cacheKey 键
     * @param array $paramKeyValArr 会作为键的关键参数值数组 --一维数组
     * @param int 选填 $operate 操作 1 转为json 2 序列化 ;
     * @return mixed ;; false失败
     */
    public static function getCacheData($pre, &$cacheKey, $paramKeyValArr, $operate){
        $actionMethod = self::getActionMethod();// 当前控制器方法名
        array_push($paramKeyValArr, $pre);
        array_push($paramKeyValArr, $actionMethod);
        $temArr = [];
        foreach ( $paramKeyValArr as $k => $v) {
            if(! is_string($v) && ! is_numeric($v)){
                $v = serialize($v);
            }
            array_push($temArr, $k . '$@' . $v);
        }
        $cacheKey = md5(implode("#!%", $temArr));
        return self::getRedis($pre .$cacheKey, $operate);
    }

    /**
     * 保存redis值-json/序列化保存
     * @param string 必填 $pre 前缀
     * @param string $key 键 null 自动生成
     * @param string 选填 $value 需要保存的值，如果是对象或数组，则序列化
     * @param int 选填 $expire 有效期 秒 <=0 长期有效
     * @param int 选填 $operate 操作 1 转为json 2 序列化
     * @return string $key [含前缀]
     * @author zouyan(305463219@qq.com)
     */
    public static function cacheData($pre = '', $key = null, $value = '', $expire = 0, $operate = 1)
    {
        // 缓存数据
        return self::setRedis($pre, $key, $value, $expire , $operate); // 1天
    }

    /**
     * 列出日期區間的 所有日期清單
     * @param string $first 开始日期 YYYY-MM-DD
     * @param string $last 结束日期 YYYY-MM-DD
     * @param string $step 步长 '+1 day'
     * @param string $format 日期格式化 'Y-m-d'
     * @return array $dates  区间内的日期[含]
     * @author zouyan(305463219@qq.com)
     */
    public static function dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d')
    {
        $dates   = [];
        $current = strtotime($first);
        $last    = strtotime($last);

        while ($current <= $last) {
            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }


    /**
     * 列出日期區間的 所有月清單
     * @param string $start 开始日期 YYYY-MM-DD
     * @param string $end 结束日期 YYYY-MM-DD
     * @return array $dates  区间内的月[含] [201809,201810]
     * @author zouyan(305463219@qq.com)
     */
    public static function showMonthRange($start, $end)
    {
        $end = date('Ym', strtotime($end)); // 转换为月
        $range = [];
        $i = 0;
        do {
            $month = date('Ym', strtotime($start . ' + ' . $i . ' month'));
            //echo $i . ':' . $month . '<br>';
            $range[] = $month;
            $i++;
        } while ($month < $end);

        return $range;
    }

    /**
     * 列出日期區間的 所有年清單
     * @param string $start 开始日期 YYYY-MM-DD
     * @param string $end 结束日期 YYYY-MM-DD
     * @return array $dates  区间内的年[含] [2015,2016,2017,2018]
     * @author zouyan(305463219@qq.com)
     */
    public static function showYearRange($start, $end)
    {
        $end = date('Y', strtotime($end)); // 转换为月
        $range = [];
        $i = 0;
        do {
            $year = date('Y', strtotime($start . ' + ' . $i . ' year'));
            //echo $i . ':' . $year . '<br>';
            $range[] = $year;
            $i++;
        } while ($year < $end);

        return $range;
    }

    /**
     * 你上面的方法我觉得不怎么好，介绍一下我写的一个方法。方法函数如下，这样当你要的结果001的话，方法：dispRepair('1',3,'0')
     * 功能：补位函数
     * @param string str 原字符串
     * @param string len 新字符串长度
     * @param string $msg 填补字符
     * @param string $type 类型，0为后补，1为前补
     * @return array $dates  区间内的年[含] [2015,2016,2017,2018]
     * @author zouyan(305463219@qq.com)
     */
    public static function dispRepair($str, $len, $msg, $type = '1') {
        $length = $len - strlen($str);
        if ($length<1) return $str;
        if ($type == 1) {
            $str = str_repeat($msg, $length) . $str;
        } else {
            $str .= str_repeat($msg, $length);
        }
        return $str;
    }

    /**
     * 功能：获得日期
     * @param int $dateType 日期类型 1本周一;2 本周日;3 上周一;4 上周日;5 本月一日;6 本月最后一日;7 上月一日;8 上月最后一日
     * @return mixed $date 日期
     * @author zouyan(305463219@qq.com)
     */
    public static function getDateByType($dateType){
        switch($dateType){
            case 1://1本周一;
                return date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)); //w为星期几的数字形式,这里0为周日
                break;
            case 2://2 本周日;
                return date('Y-m-d', (time() + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600)); //同样使用w,以现在与周日相关天数算
                break;
            case 3://3 上周一;
                return date('Y-m-d', strtotime('-1 monday', time())); //无论今天几号,-1 monday为上一个有效周未
                break;
            case 4:// 4 上周日;
                return date('Y-m-d', strtotime('-1 sunday', time())); //上一个有效周日,同样适用于其它星期;
                break;
            case 5:// 5 本月一日;
                return date('Y-m-d', strtotime(date('Y-m', time()) . '-01 00:00:00')); //直接以strtotime生成;
                break;
            case 6:// 6 本月最后一日;
                return date('Y-m-d', strtotime(date('Y-m', time()) . '-' . date('t', time()) . ' 00:00:00')); //t为当月天数,28至31天
                break;
            case 7:// 7 上月一日;
                return date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m', time()) . '-01 00:00:00'))); //本月一日直接strtotime上减一个月;
                break;
            case 8:// 8 上月最后一日
                return date('Y-m-d', strtotime(date('Y-m', time()) . '-01 00:00:00') - 86400); //本月一日减一天即是上月最后一日;
                break;
            default:
                break;
        }
        return '';
    }
}
