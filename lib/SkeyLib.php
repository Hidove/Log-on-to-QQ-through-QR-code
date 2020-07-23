<?php

// +----------------------------------------------------------------------
// | Hidove [ www.hidove.cn ]
// +----------------------------------------------------------------------
// | Author: Ivey <loliconla@qq.com>
// +----------------------------------------------------------------------
// | Date: 2020-7-23 22:12:10
// +----------------------------------------------------------------------

require "common.php";

class SkeyLib
{
    public function getQrsigCookie($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $return = curl_exec($ch);
        curl_close($ch);
        $cookie = get_mid_str($return, "Set-Cookie: qrsig=", ';');
        $return = [
            'data' => get_right_str($return, ";\r\n\r\n"),
            'cookie' => $cookie,
        ];
        return $return;
    }

    public function getSkeyCookie($url, $header = array('Content-Type: application/json'))
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $response = curl_exec($ch);
        curl_close($ch);
        $array = substr($response, strpos($response, 'ptuiCB('));
        $array = str_replace('\'', '', $array);
        $array = explode(',', $array);
        try {
            $msg = $array[4];
            $cookie = '';
            if (strpos($response, '登录成功') !== false) {
                preg_match_all("~Set-Cookie: (.+?)\r\n~", $response, $matches);
                foreach ($matches[1] as $value) {
                    $cookie .= $value;
                }
            }
        } catch (\Exception $e) {
            $cookie = '';
            $msg = $e->getMessage();
        }

        if (empty($cookie)){
            $response = [
                'code' => 400,
                'msg' => $msg,
            ];
        }else{
            $response = [
                'code' => 200,
                'cookie' => $cookie,
                'msg' => $msg,
            ];
        }
        return $response;
    }

    public function uniord($str, $from_encoding = false)
    {
        $from_encoding = $from_encoding ? $from_encoding : 'UTF-8';

        if (strlen($str) == 1) {
            return ord($str);
        }


        $str = mb_convert_encoding($str, 'UCS-4BE', $from_encoding);
        $tmp = unpack('N', $str);
        return $tmp[1];
    }

    /**
     * Times33 Hash function
     * @param $str
     * @return int
     */
    public function hash33($t)
    {
        for ($e = 0, $i = 0, $n = strlen($t); $i < $n; ++$i) {
//        $e += ($e << 5)+  uniord(mb_substr($t,$i,1,'utf-8'));
            $tmp = $e << 5;
            $tmp = unpack("L", pack("L", $tmp))[1];
            $e += $tmp + $this->uniord(mb_substr($t, $i, 1, 'utf-8'));
        }
        return 2147483647 & $e;
    }

}