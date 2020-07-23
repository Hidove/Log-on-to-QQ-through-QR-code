<?php

// +----------------------------------------------------------------------
// | Hidove [ www.hidove.cn ]
// +----------------------------------------------------------------------
// | Author: Ivey <loliconla@qq.com>
// +----------------------------------------------------------------------
// | Date: 2020-7-23 22:15:54
// +----------------------------------------------------------------------

require "lib/QQLogin.php";

$QQLogin = new QQLogin();

if (!empty($_GET['qrsig'])) {
    header('Content-type: application/json');
    die(json_encode( $QQLogin->getSkey($_GET['qrsig']),JSON_UNESCAPED_UNICODE));

}


$QrcodeData = $QQLogin->getLoginQrcode();

$qrcode = $QrcodeData['qrcode'];

$qrsigBASE64 = base64_encode($QrcodeData['cookie']);

$html=<<<EOT
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>刷新QQ登录信息 - Hidove图床</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="/favicon.ico"/>
    <script src="//lib.baomitu.com/jquery/3.4.1/jquery.min.js"></script>
    <link href="//lib.baomitu.com/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="//lib.baomitu.com/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body class="container ">
<div class="card mt-5">
    <div class="card-body text-center">
        <p class="card-text">打开手机QQ扫描下方二维码登录即可刷新Cookie</p>
        <div class="text-center my-3">
            <img src="{$qrcode}">
        </div>
        <div class="text-center my-3">
            <button type="button" class="btn btn-outline-dark" onclick="location=''">刷新二维码</button>
        </div>
    </div>
    <div class="alert alert-primary" role="alert" id="msg">
        正在请求中，请稍等。。。
    </div>
</div>
<script>
    refresh();
    function refresh() {
        $.ajax({
            url: '?qrsig={$qrsigBASE64}',
            success: function (data) {
                console.log(data)
                if (data.msg.indexOf("登录成功") == -1 && data.msg.indexOf("已失效") == -1 && data.msg.indexOf("拒绝") == -1){
                    setTimeout(function () {
                        refresh();
                    },3000);
                }
                $('#msg').html(data.msg);
            }
        });
    }
</script>
</body>
</html>
EOT;
echo $html;