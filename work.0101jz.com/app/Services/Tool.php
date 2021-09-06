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
        $newArr = [];
        foreach($keys as $new_k => $old_k){
            if(!isset($array[$old_k])){// 不存在
                if($needNotIn){// true：空值
                    $newArr[$new_k] = '';
                }
            }else{// 存在
                $newArr[$new_k] = $array[$old_k];
            }
        }
        $array = $newArr;
        return $newArr;
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
     * @param keyPush 键加入无素 1 $pre 键前缀 2 当前控制器方法名;
     * @return mixed ;; false失败
     */
    public static function getCacheData($pre, &$cacheKey, $paramKeyValArr, $operate, $keyPush = 0){
        if( ($keyPush & 1) == 1)  array_push($paramKeyValArr, $pre);

        if( ($keyPush & 2) == 2){
            $actionMethod = self::getActionMethod();// 当前控制器方法名  App\Http\Controllers\weixiu\IndexController@index
            array_push($paramKeyValArr, $actionMethod);
        }
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
     * @param int $dateType 日期类型 1本周一;2 本周日;3 上周一;4 上周日;5 本月一日;6 本月最后一日;7 上月一日;8 上月最后一日;9 本年一日;10 本年最后一日;11 上年一日;12 上年最后一日
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
                // return date('Y-m-d', strtotime('-1 wednesday', time())); //无论今天几号,-1 monday为上一个有效周未
                return date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600) - 7*24*60*60); //本周一 减七天;
                break;
            case 4:// 4 上周日;
                // return date('Y-m-d', strtotime('-1 sunday', time())); //上一个有效周日,同样适用于其它星期;
                return date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600) - 1*24*60*60); //本周一 减一天;
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
            case 9:// 9 本年一日
                return date("Y-01-01");
                break;
            case 10:// 10 本年最后一日
                return date("Y-12-31");
                break;
            case 11:// 11 上年一日
                return date('Y-01-01', strtotime(date('Y-m-d') . ' -1 year'));
                break;
            case 12:// 12 上年最后一日
                return date('Y-12-31', strtotime(date('Y-m-d') . ' -1 year'));
                break;
            default:
                break;
        }
        return '';
    }

    /**
     * 功能：开始、结束日期 判断
     * @param string $begin_date 开始日期
     * @param string $end_date 结束日期
     * @param int $judge_type 1 判断开始日期不能为空 ; 2 判断结束日期不能为空；
     *                        4 开始日期 不能大于 >  当前日；8 开始日期 不能等于 =  当前日；16 开始日期 不能小于 <  当前日
     *                        32 结束日期 不能大于 >  当前日；64 结束日期 不能等于 =  当前日；128 结束日期 不能小于 <  当前日
     *                        256 开始日期 不能大于 >  结束日期；512 开始日期 不能等于 =  结束日期；1024 开始日期 不能小于 <  结束日期
     * @param string $errDo 错误处理方式 1 throws 2直接返回错误
     * @param string $nowTime 比较日期 格式 Y-m-d,默认为当前日期 Y-m-d
     * @param string $dateName 日期(默认); 时间
     * @return boolean 结果 true通过判断; sting 具体错误 ； throws 错误
     * @author zouyan(305463219@qq.com)
     */
    public static function judgeBeginEndDate($begin_date, $end_date, $judge_type = 0, $errDo = 1, $nowTime = '', $dateName = '日期' ){
//        $begin_date = Common::get($request, 'begin_date');// 开始日期
//        $end_date = Common::get($request, 'end_date');// 结束日期
        if(empty($nowTime)) $nowTime = date('Y-m-d');
        $nowTimeUnix = judgeDate($nowTime);

        if( ($judge_type & 1) == 1 && empty($begin_date)){// 1 判断开始日期不能为空
            $errMsg = '开始' . $dateName . '不能为空!';
            if($errDo == 1) throws($errMsg);
            return $errMsg;
        }

        if (!empty($begin_date)) {
            $begin_date_unix = judgeDate($begin_date);
            if($begin_date_unix === false){
                $errMsg = '开始' . $dateName . '不是有效' . $dateName . '!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }
            // 4 开始日期 不能大于 >  当前日
            if(($judge_type & 4) == 4 && $begin_date_unix > $nowTimeUnix ){
                $errMsg = '开始' . $dateName . '不能大于' . $dateName . '[' . $nowTime . ']!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }

            // 8 开始日期 不能等于 =  当前日
            if(($judge_type & 8) == 8 && $begin_date_unix == $nowTimeUnix ){
                $errMsg = '开始' . $dateName . '不能等于' . $dateName . '[' . $nowTime . ']!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }

            // 16 开始日期 不能小于 <  当前日
            if(($judge_type & 16) == 16 && $begin_date_unix < $nowTimeUnix ){
                $errMsg = '开始' . $dateName . '不能小于' . $dateName . '[' . $nowTime . ']!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }
        }

        if( ($judge_type & 2) == 2 && empty($end_date)){//2 判断结束日期不能为空；
            $errMsg = '结束' . $dateName . '不能为空!';
            if($errDo == 1) throws($errMsg);
            return $errMsg;
        }

        if (!empty($end_date)) {
            $end_date_unix = judgeDate($end_date);
            if($end_date_unix === false){
                $errMsg = '结束' . $dateName . '不是有效' . $dateName . '!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }

            // 32 结束日期 不能大于 >  当前日
            if(($judge_type & 32) == 32 && $end_date_unix > $nowTimeUnix ){
                $errMsg = '结束' . $dateName . '不能大于' . $dateName . '[' . $nowTime . ']!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }

            // 64 结束日期 不能等于 =  当前日
            if(($judge_type & 64) == 64 && $end_date_unix == $nowTimeUnix ){
                $errMsg = '结束' . $dateName . '不能等于' . $dateName . '[' . $nowTime . ']!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }

            // 128 结束日期 不能小于 <  当前日
            if(($judge_type & 128) == 128 && $end_date_unix < $nowTimeUnix ){
                $errMsg = '结束' . $dateName . '不能小于' . $dateName . '[' . $nowTime . ']!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }
        }

        if(!empty($begin_date) && !empty($end_date) ){

            // 256 开始日期 不能大于 >  结束日期；
            if(($judge_type & 256) == 256 && $begin_date_unix > $end_date_unix ){
                $errMsg = '开始' . $dateName . '不能大于结束' . $dateName . '!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }

            // 512 开始日期 不能等于 =  结束日期；
            if(($judge_type & 512) == 512 && $begin_date_unix == $end_date_unix ){
                $errMsg = '开始' . $dateName . '不能等于结束' . $dateName . '!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }

            // 1024 开始日期 不能小于 <  结束日期
            if(($judge_type & 1024) == 1024 && $begin_date_unix < $end_date_unix ){
                $errMsg = '开始' . $dateName . '不能小于结束' . $dateName . '!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }
        }
        return true;
    }

    /**
     * 功能：验证数据
     * @param array $valiDateParam 需要验证的条件
        $valiDateParam= [
            //["input"=>$_POST["title"],"require"=>"true","message"=>'闪购名称不能为空'],  -- 必填  -- require是否必填，可以与下面的一方一起参与验证
            ["input"=>$_POST["state"],"require"=>"false","validator"=>"custom","regexp"=>"/^([01]|10)$/","message"=>'闪购状态值有误'],--正则
            ["input"=>$_POST["title"],"require"=>"false","validator"=>"length","min"=>"1","max"=>"160","message"=>'闪购名称长度为1~ 160个字符'],--判断长度
            ["input"=>$_POST["title"],"require"=>"false","validator"=>"compare","operator"=>"比较符>=<=","to"=>"被比较值","message"=>'闪购名称不能大于10'],--比较
            ["input"=>$_POST["title"],"require"=>"false","validator"=>"range","min"=>"最小值1","max"=>"最大值10","message"=>'闪购值必须大于等于1且小于等于10'],--范围
            ["input"=>$_POST["market_id"],"require"=>"false","validator"=>"integer","message"=>'闪购地编号必须为数值'], --配置好的
        ];
     * @param string $errDo 错误处理方式 1 throws 2直接返回错误
     * @return string  错误信息 ，没有错，则为空
     * @author zouyan(305463219@qq.com)
     */
    public static function dataValid($valiDateParam = [], $errDo = 1) {
        if(empty($valiDateParam) || (!is_array($valiDateParam))){
            return false;
        }
        $validateObj = new Validate();
        $validateObj->validateparam = $valiDateParam;
        // return $validateObj->validate();
        $error = $validateObj->validate();
        if ($error != ''){
            if($errDo == 1) throws($error);
            return $error;
            // output_error($error);
         }
         return '';
    }

    /**
     * 功能：获得框文件夹绝对路径
     * @param string $pathKey 路径关键字
     *       app   app目录的绝对路径 srv/www/work/work.0101jz.com/app
     *       base  项目根目录的绝对路径 /srv/www/work/work.0101jz.com
     *       base   'public'  相对于应用目录的给定文件生成绝对路径 /srv/www/work/work.0101jz.com/public
     *       config 应用配置目录的绝对路径  /srv/www/work/work.0101jz.com/config
     *       database 应用数据库目录的绝对路径 /srv/www/work/work.0101jz.com/database
     *       public public目录的绝对路径 /srv/www/work/work.0101jz.com/public
     *       storage   storage目录的绝对路径 /srv/www/work/work.0101jz.com/storage
     *       storage    'app/file.txt'   还可以使用storage_path函数生成相对于storage目录的给定文件的绝对路径 /srv/www/work/work.0101jz.com/storage/app/file.txt
     * @param string $dir 目录或文件
     * @return string  绝对路径
     * @author zouyan(305463219@qq.com)
     */
    public static function getPath($pathKey = '', $dir = ''){
        $returnPath = '';
        switch (strtolower($pathKey)) {
            case 'app':
                // app_path();//app目录的绝对路径 srv/www/work/work.0101jz.com/app
                $returnPath = app_path();
                break;
            case 'base':
                // base_path();// 项目根目录的绝对路径 /srv/www/work/work.0101jz.com
                // $path = base_path('vendor/bin'); // 相对于应用目录的给定文件生成绝对路径
                //    base_path('public') ;// /srv/www/work/work.0101jz.com/public
                if(empty($dir)){
                    $returnPath = base_path();
                }else{
                    $returnPath = base_path($dir);
                }
                break;
            case 'config':
                // config_path();  // 应用配置目录的绝对路径  /srv/www/work/work.0101jz.com/config
                $returnPath = config_path();
                break;
            case 'database':
                // database_path();// 应用数据库目录的绝对路径 /srv/www/work/work.0101jz.com/database
                $returnPath = database_path();
                break;
            case 'public':
                // public_path(); // public目录的绝对路径 /srv/www/work/work.0101jz.com/public
                $returnPath = public_path();
                break;
            case 'storage':
                // storage_path(); // storage目录的绝对路径 /srv/www/work/work.0101jz.com/storage
                // storage_path('app/file.txt')还可以使用storage_path函数生成相对于storage目录的给定文件的绝对路径 /srv/www/work/work.0101jz.com/storage/app/file.txt
                if(empty($dir)){
                    $returnPath = storage_path();
                }else{
                    $returnPath = storage_path($dir);
                }
                break;
            default:
                break;
        }
        return $returnPath;
    }

    /**
     * 功能：对二维数组,按指定多个下标进行排序
     * @param array $data 需要排序的二维数组[如数据表数据-二维数据]
     * @param array  $keys ,用来排序的字段
     *      key:字段下标 ;
     *      sort:排序顺序标志;asc[按照上升顺序排序]-默认,desc[按照下降顺序排序]；
     *      type: 排序类型标志;regular[将项目按照通常方法比较]-默认,numeric[将项目按照数值比较],string[将项目按照字符串比较]
     *      array(
     *          array(key=>col1, sort=>desc),
     *          array(key=>col2, type=>numeric)
     *      )
     * @return array 排序后的二维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function php_multisort($data, $keys){
        if(empty($data) || (!is_array($data)))  return $data;
        // List As Columns
        foreach ($data as $key => $row) {
            foreach ($keys as $k){
                $cols[$k['key']][$key] = $row[$k['key']];
            }
        }
        // List original keys
        $idkeys=array_keys($data);
        // Sort Expression
        $i=0;
        $sort = '';
        foreach ($keys as $k){
            if($i>0){$sort.=',';}
            $sort.='$cols["'.$k['key'].'"]';
            if($k['sort']){$sort.=',SORT_'.strtoupper($k['sort']);}
            if($k['type']){$sort.=',SORT_'.strtoupper($k['type']);}
            $i++;
        }
        $sort.=',$idkeys';
        // Sort Funct
        $sort='array_multisort('.$sort.');';
        eval($sort);
        // Rebuild Full Array
        foreach($idkeys as $idkey){
            $result[$idkey]=$data[$idkey];
        }
        return $result;
    }

    /**
     *
     * 中英混合的字符串字符的个数
     * @param string $str 需要判断的字符串
     * @param int $operateType 操作类型 1：字符个数【中文算一个】； 2字符位长度 [默认]
     * @param string $encoding
     * @return int
     */
    public static function getStrNum($str, $operateType = 2, $encoding = "utf8"){
        if($operateType == 1){
            return mb_strlen($str, $encoding);
        }
        return strlen($str);
    }

    /**
     *
     * 中英混合的字符串截取--返回的时指定的字符的个数【不是位长度】
     * @param 待截取字符串 $sourcestr
     * @param 截取长度 $cutlength
     */
    public static function subStr($sourcestr, $cutlength) {
        $returnstr = '';//待返回字符串
        $i = 0;
        $n = 0;
        $str_length = strlen ( $sourcestr ); //字符串的字节数
        if($str_length <= $cutlength) return $sourcestr;
        while ( ($n < $cutlength) and ($i <= $str_length) ) {
            $temp_str = substr ( $sourcestr, $i, 1 );
            $ascnum = Ord ( $temp_str ); //得到字符串中第$i位字符的ascii码
            if ($ascnum >= 224) {//如果ASCII位高与224，
                $returnstr = $returnstr . substr ( $sourcestr, $i, 3 ); //根据UTF-8编码规范，将3个连续的字符计为单个字符
                $i = $i + 3; //实际Byte计为3
                $n ++; //字串长度计1
            } elseif ($ascnum >= 192){ //如果ASCII位高与192，
                $returnstr = $returnstr . substr ( $sourcestr, $i, 2 ); //根据UTF-8编码规范，将2个连续的字符计为单个字符
                $i = $i + 2; //实际Byte计为2
                $n ++; //字串长度计1
            } elseif ($ascnum >= 65 && $ascnum <= 90) {//如果是大写字母，
                $returnstr = $returnstr . substr ( $sourcestr, $i, 1 );
                $i = $i + 1; //实际的Byte数仍计1个
                $n ++; //但考虑整体美观，大写字母计成一个高位字符
            }elseif ($ascnum >= 97 && $ascnum <= 122) {
                $returnstr = $returnstr . substr ( $sourcestr, $i, 1 );
                $i = $i + 1; //实际的Byte数仍计1个
                $n ++; //但考虑整体美观，大写字母计成一个高位字符
            } else {//其他情况下，半角标点符号，
                $returnstr = $returnstr . substr ( $sourcestr, $i, 1 );
                $i = $i + 1;
                $n = $n + 1;// 0.5;
            }
        }
        return $returnstr;
    }

    /**
     * 设置临时改变php内存及执行时间
     * @param string $memory_limit // 临时设置最大内存占用为 3072M 3G
     * @param int $max_execution_time // 设置脚本最大执行时间 为0 永不过期
     *
     */
    public static function phpInitSet($memory_limit = '3072M', $max_execution_time = 0){
        ini_set('memory_limit', $memory_limit);// '3072M');    // 临时设置最大内存占用为 3072M 3G
        ini_set("max_execution_time", $max_execution_time);// 0);
        set_time_limit($max_execution_time);// 0);   // 设置脚本最大执行时间 为0 永不过期
    }

    /**
     * 对位运算，移除某个位操作 -- 存在则移除，不存在不移除 ，返回新的数
     * @param $bitVal  要操作的数【位】   1 | 2 | 4;
     * @param $removeBitVal 需要移除的数【位】 4
     * @return int
     */
    public static function bitRemoveVal($bitVal, $removeBitVal){
//        $bitVal = 1 | 2 | 4;
//        $removeBitVal = 8;
        $bitVal = (($bitVal & $removeBitVal ) == $removeBitVal) ? ($bitVal ^ $removeBitVal) : $bitVal;
        return $bitVal;
    }

    /**
     * 判断一个数是不是 1，2，4，8...
     *   只是判断一次可用这个，如果有多次，则用 isBitNumByArr方法判断比较好
     * @param int $val  需要判断的数
     * @param int  $maxNum 最大执行的次数 如 int: 31  bigint: 63[默认]
     * @param int  $bitNum 当前执行的次数 0[默认]，1，2，3，4，5... -- 通常不用传此值，用默认值 0 就可以从 2 0次方1开始
     * @return boolean  true:是位数值; false:不是位数值
     */
    public static function isBitNum($val, $maxNum = 63, $bitNum = 0){
        $recordJudgeNum = 2**$bitNum;// pow(2,$bitNum);
        if( $val  == $recordJudgeNum) return true;

        if( ($bitNum + 1) >= $maxNum) return false;// 已经达到最大执行次数

        if(static::isBitNum($val, $maxNum, ++$bitNum)) return true;

        return false;
    }

    /**
     * 判断一个数是不是 1，2，4，8...,通过先获得所有的位一维数组
     *   只是判断一次可用这个，如果有多次，则用 isBitNumByArr方法判断比较好
     * @param int $val  需要判断的数
     * @param array $bitNumArr   所有的位一维数组; 默认为空数组--会自动去获取数组值； 引用传值，可以获取到位数组后继续使用
     * @param int  $maxNum 最大执行的次数 如 int: 31  bigint: 63[默认]
     * @return boolean  true:是位数值; false:不是位数值
     */
    public static function isBitNumByArr($val, &$bitNumArr = [], $maxNum = 63){
        if(empty($bitNumArr)) $bitNumArr = static::getBitArr($maxNum);
        if(in_array($val, $bitNumArr)) return true;
        return false;
    }

    /**
     * 获得位数组 --一维 [1，2，4，8...]
     * @param int  $maxNum 最大执行的次数 如 int: 31  bigint: 63[默认]
     * @param int  $minBitNum 起始的位值 ；默认 1
     * @return array  位数组 --一维 [1，2，4，8...]
     */
    public static function getBitArr($maxNum = 63, $minBitNum = 1){
        $reArr = [];
        if($minBitNum < 1) $minBitNum = 1;
        for($i = ($minBitNum - 1); $i< $maxNum; $i++){
            array_push($reArr, 2**$i);
        }
        return $reArr;
    }

    /**
     * laravel-判断是否在cli模式运行
     * @return boolean 命令行： true; 非命令行 :false
     */
    public static function isCLIDoing(){
        // 有时候，我们需要判断是否是在命令行环境中执行，可以使用：
        // if(app()->runningInConsole()){// 命令行： true; 非命令行 :false
        //     // 运行在命令行下
        //     return true;
        //  }
        // return false;
        //当然，在 PHP 中，你永远可以使用 PHP 原生的方法来检测：
        // 命令行： "cli"; 非命令行 :"fpm-fcgi"
        if(strpos(php_sapi_name(), 'cli') !== false){
            // 运行在命令行下
            return true;
        }
        return false;
    }

    /**
     * 一个值如果不是数组，则转为数组
     * @param mixed $paramVal 需要处理的值  可以是字符串；数组；字符
     * @param string $splitText  如果值为字符是，转数组时的分隔符，默认逗号,
     * @return array 转换好的数组
     */
    public static function paramToArrVal($paramVal, $splitText = ','){
        // 如果是字符，则转为数组
        if(is_string($paramVal) || is_numeric($paramVal)){
            if(strlen(trim($paramVal)) > 0){
                $paramVal = explode($splitText ,$paramVal);
            }
        }
        if(!is_array($paramVal)) $paramVal = [];
        return $paramVal;
    }

    // 引用处理 一个值如果不是数组，则转为数组
    public static function valToArrVal(&$paramVal, $splitText = ','){
        $paramVal = static::paramToArrVal($paramVal, $splitText);
        return $paramVal;
    }


    /**
     * 一维数组清除空值
     *
     * @param array $array
     * @return array
     */
    public static function arrClsEmpty(&$array){
        foreach($array as $k => $v){
            if(is_null($v) || trim($v) === '') unset($array[$k]);
        }
        return $array;
    }

    /**
     * 将位数组，合并为一个数值
     * @param array $bitArr 位值数组 【1、2、、4、8...】
     * @return int 合并后的数值
     */
    public static function bitJoinVal($bitArr){
        $bitJoinVal = 0;
        Tool::arrClsEmpty($bitArr);
        foreach($bitArr as $bitVal){
            $bitJoinVal = $bitJoinVal | $bitVal;
        }
        return $bitJoinVal;
    }

    // 引用处理 将位数组，合并为一个数值
    public static function bitArrToInt(&$bitArr){
        $bitArr = static::bitJoinVal($bitArr);
        return $bitArr;
    }

    /**
     * 根据位所有值数组及指定的位，获得指定位的数组
     * @param array $bitKVArr 位所有值数组 ；如 [ '1' => '开通', '2' => '关闭', '4' => '作废']
     * @param int $bitVal 要获得的位值 ; 如 1 ｜ 2  或 1 ｜ 4
     * @return array 指定位的数组 如 [ '1' => '开通', '4' => '作废']
     */
    public static function getBitValArr($bitKVArr, $bitVal){
        $return_arr = [];
        if($bitVal <= 0 || empty($bitKVArr)) return $return_arr;
        foreach($bitKVArr as $k => $v){
            if(($k & $bitVal) == $k)  $return_arr[$k] = $v;
        }
        return $return_arr;
    }

    /**
     * 根据位所有值数组及指定的位，获得指定位的值的文字【多个值用、号分隔】--也可以操作非位值数组【返回值下标的文字】
     * @param array $bitKVArr 位所有值数组 ；如 [ '1' => '开通', '2' => '关闭', '4' => '作废']；
     *                --也可以不是位值数组，是其它数组 [ '1' => '开通', '2' => '关闭', '3' => '作废'],则仅返回值下标的文字
     * @param int $bitVal 要获得的位值 ; 如 1 ｜ 2  或 1 ｜ 4 ； 也可以是其它值 如 3
     * @param string $splitStr 值分割字符 默认 '、'
     * @return string
     */
    public static function getBitVals($bitKVArr, $bitVal, $splitStr = '、'){
        if(empty($bitKVArr)) return '';
        $isBitArr = true;
        $bitNumArr = [];
        foreach($bitKVArr as $k => $v){
            if(!static::isBitNumByArr($k, $bitNumArr)){
                $isBitArr = false;
                break;
            }
        }
        if($isBitArr){
            return implode($splitStr, static::getBitValArr($bitKVArr, $bitVal));
        }
        return $bitKVArr[$bitVal] ?? '';
    }

    /**
     * 根据KV数组，生成对应的正则 /^([1248]|16|32)$/
     * @param array $bitKVArr 位所有值数组 ；如 [ '1' => '开通', '2' => '关闭', '4' => '作废']；
     * @param array $bigArr 已有的正则项数组--一维数组
     * @param boolean $appendSlash  是否需要加前后的 斜杠 true:需要  ; false:不需要【默认】
     * @return string
     */
    public static function getPregByKVArr($bitKVArr, &$bigArr = [], $appendSlash = false){
        $keyArr = array_keys($bitKVArr);
        return static::getPregByKeyArr($keyArr, $bigArr, $appendSlash);
    }

    /**
     * 根据key数组[一维]，生成对应的正则 /^([1248]|16|32)$/
     * @param array $keyArr 位所有值数组 -- 一维数组 ；如 [ '1' , '2' , '4']；
     * @param array $bigArr 已有的正则项数组--一维数组
     * @param boolean $appendSlash  是否需要加前后的 斜杠 true:需要  ; false:不需要【默认】 ；前端js new RegExp(reg2); 的字符时不需要 前后 加 斜杠
     * @return string
     */
    public static function getPregByKeyArr($keyArr, &$bigArr = [], $appendSlash = false){
        $minIntArr = [];// 小于10的数字数组
        // $bigArr = [];// 大于 10的数字或其它
        foreach($keyArr as $keySingle){
            if(is_numeric($keySingle) && $keySingle >= 0 && $keySingle < 10){
                array_push($minIntArr, $keySingle);
            }else{
                array_push($bigArr, $keySingle);
            }
        }
        $bigArrEmpty = true;
        if(!empty($bigArr)){
            $bigArrEmpty = false;
        }
        if(!empty($minIntArr)) array_unshift($bigArr, '[' . implode('', $minIntArr) . ']');
        if($bigArrEmpty){
            if($appendSlash){
                return "/^" . implode('', $bigArr) . "$/";// /^[12]$/
            }else{
                return "^" . implode('', $bigArr) . "$";// ^[12]$
            }
        }

        if($appendSlash) {
            return "/^(" . implode('|', $bigArr) . ")$/";// /^([1248]|16|32)$/ ；
        }else{
            return "^(" . implode('|', $bigArr) . ")$";// ^([1248]|16|32)$ ；
        }
    }

    /**
     * 校验日期格式是否正确
     * @param string $date 日期
     * @param string $formats 需要检验的格式数组
     * @return boolean
     */
    public static function isDate($date, $formats = ["Y-m-d", "Y/m/d","Y-m-d H:i:s"]) {
        $unixTime = strtotime($date);
        if (!$unixTime) { //strtotime转换不对，日期格式显然不对。
            return false;
        }
        //校验日期的有效性，只要满足其中一个格式就OK
        foreach ($formats as $format) {
            if (date($format, $unixTime) == $date) {
                return true;
            }
        }
        return false;
    }

    /**
     *  字符编码转换成utf8
     *   // mb_convert_encoding('要转编码的字符串','目标编码','原编码')
     *   // mb_detect_encoding('字符串')：
     *   // mb_convert_encoding($str,'utf-8',mb_detect_encoding($str))
     * @param string $str 需要转的字符
     * @return string 返回转好的字符
     */
    public static function strToUtf8($str){
        $encode = mb_detect_encoding($str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        if($encode == 'UTF-8'){
            return $str;
        }else{
            return mb_convert_encoding($str, 'UTF-8', $encode);
        }
    }

    /**
     *  对可能有 2021/06/11 12:43:36  2021年06月11日 12:43:36 格式 转为  2021-06-11 12:43:36
     * @param string $dataTimeStr 需要转的字符  可能有 2021/06/11 12:43:36  2021年06月11日 12:43:36
     * @return string 返回转好的字符 2021-06-11 12:43:36
     */
    public static function dataTimeFormat($dataTimeStr){
        $reStr = $dataTimeStr;
        // 优化下，如果含有 / ，则转为 -
        if(strpos($reStr, '/') !== false){
            $reStr = str_replace(['/'], ['-'], $reStr);
        }
        // 如果 ratify_date 内容是 2021年03月05日，则进行处理
        if(strpos($reStr, '年') !== false || strpos($reStr, '月') !== false || strpos($reStr, '日') !== false){
            $reStr = str_replace(['年', '月', '日'], ['-', '-', ''], $reStr);
        }
        return $reStr;
    }


    /**
     * 功能：日期 加/减操作
     * @param string $operateDate 操作日期/时间;// 为空，则操作当前时间
     * @param array $oprates 操作类型 一维数组, 下面空格拼接执行
     *   // +1 day +1 hour +1 minute  可以随便自由组合，以达到任意输出时间的目的
     *   // -1 day  ---昨天  // 可以修改参数1为任何想需要的数  day也可以改成year（年），month（月），hour（小时），minute（分），second（秒）
     *   // +1 day  ---明天
     *   // +1 week  ---一周后
     *   // +1 week 2 days 4 hours 2 seconds  ---一周零两天四小时两秒后
     *  // next Thursday   ---下个星期四
     *   // last Monday  --- 上个周一
     *   // last month  ---一个月前
     *   // +1 month  ---一个月后
     *   // +10 year  ---十年后
     * @param string $format 返回数据格式化 "Y-m-d H:i:s","Y-m-d","H:i:s"
     * @param string $errDo 错误处理方式 1 throws 2直接返回错误
     * @param string $dateName 日期(默认); 时间
     * @return mixed  sting 具体错误 ； throws 错误
     * @author zouyan(305463219@qq.com)
     */
    public static function addMinusDate($operateDate, $oprates = [], $format = 'Y-m-d H:i:s', $errDo = 1, $dateName = '时间')
    {
        // date_default_timezone_set('PRC'); //默认时区
        if(empty($operateDate)) $operateDate = date('Y-m-d H:i:s');
        // 开始时间
        $date_unix = judgeDate($operateDate);
        if($date_unix === false){
            $errMsg = '开始' . $dateName . '不是有效' . $dateName . '!';
            if($errDo == 1) throws($errMsg);
            return $errMsg;
        }
        // date('Y-m-d', strtotime ("+1 day", strtotime('2011-11-01')))
        if(!empty($oprates)){
            return date($format, strtotime (implode(' ', $oprates), strtotime(judgeDate($date_unix, "Y-m-d H:i:s"))));
        }
        return judgeDate($date_unix, $format);
    }

}
