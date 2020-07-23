<?php

// +----------------------------------------------------------------------
// | Hidove [ www.hidove.cn ]
// +----------------------------------------------------------------------
// | Author: Ivey <loliconla@qq.com>
// +----------------------------------------------------------------------
// | Date: 2020-7-23 22:14:22
// +----------------------------------------------------------------------

require "SkeyLib.php";

class QQLogin
{
    private $skeyLib;
    public function __construct(){
        $this->skeyLib = new SkeyLib();
    }
    /** 获取二维码
    * 
    */
    public function getLoginQrcode()

    {
        // 
        $curlData = $this->skeyLib->getQrsigCookie("https://ssl.ptlogin2.qq.com/ptqrshow?appid=501038301&e=2&l=M&s=3&d=72&v=4&t=0.692298523906475&pt_3rd_aid=0");

        // $qrsig = $curlData['cookie'];
        // $qrsigBASE64 = base64_encode($qrsig);
        return [
            'qrcode'=>'data:image/png;base64,'.base64_encode($curlData['data']),
            'cookie'=>$curlData['cookie'],
        ];
    }
    /** 获取skey
     * @param $qrsig
     * @return array|bool|string
     */
    public function getSkey($qrsig)
    {
        $qrsig = base64_decode($qrsig);
        $login_sig = $qrsig;
        $ptqrtoken = $this->skeyLib->hash33($login_sig);
        $url = "https://ssl.ptlogin2.qq.com/ptqrlogin?u1=https%3A%2F%2Fim.qq.com%2FloginSuccess.html&ptqrtoken=$ptqrtoken&ptredirect=0&h=1&t=1&g=1&from_ui=1&ptlang=2052&action=0-0-1584810714289&js_ver=20021917&js_type=1&login_sig=$login_sig&pt_uistyle=40&aid=501038301&";
        $data = $this->skeyLib->getSkeyCookie($url, ["cookie: qrsig=$qrsig;"]);
        return $data;
    }
}
