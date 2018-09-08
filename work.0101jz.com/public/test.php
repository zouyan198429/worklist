<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <!-- HTTP 1.1 -->
    <meta http-equiv="pragma" content="no-cache">
    <!-- HTTP 1.0 -->
    <meta http-equiv="cache-control" content="no-cache">
    <!-- Prevent caching at the proxy server -->
    <meta http-equiv="expires" content="0">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9" />
    <meta http-equiv="Fastcgi-Cache" contect="off">

    <title>Title</title>
</head>
<body>

<?php
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
//header("Cache-Control: no-store, no-cache, must-revalidate");
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache");
//header("Fastcgi-Cache: off");

//ini_set('date.timezone','Asia/Shanghai'); // 'Asia/Shanghai' 为上海时区
date_default_timezone_set('PRC'); //设置中国时区
print_r($_GET);
print_r($_POST);
echo date('Y-m-d H:i:s');
phpinfo();
?>

</body>
</html>

