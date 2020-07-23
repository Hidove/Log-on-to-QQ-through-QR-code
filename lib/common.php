<?php

// +----------------------------------------------------------------------
// | Hidove [ www.hidove.cn ]
// +----------------------------------------------------------------------
// | Author: Ivey <loliconla@qq.com>
// +----------------------------------------------------------------------
// | Date: 2020-7-23 22:14:22
// +----------------------------------------------------------------------

//以下是取中间文本的函数
function get_mid_str($str, $leftStr, $rightStr)
{
    $left = strpos($str, $leftStr);
    //echo '左边:'.$left;
    $right = strpos($str, $rightStr, $left);
    //echo '<br>右边:'.$right;
    if ($left < 0 or $right < $left) return '';
    return substr($str, $left + strlen($leftStr), $right - $left - strlen($leftStr));
}

//以下是取右边文本的函数
function get_right_str($str, $leftStr)
{
    $left = strpos($str, $leftStr);
    return substr($str, $left + strlen($leftStr));
}

//以下是取左边边文本的函数
function get_left_str($str, $rightStr)
{
    $right = strpos($str, $rightStr);
    return substr($str, 0, $right);
}
