<?php
/**
 * @Notes:
 * @Class Exception
 * @author: FG
 * @Time: 2020/4/28 15:01
 */


namespace bajiu\weixin\common\WXBizMsgCrypt;



use Throwable;

class Exception extends \Exception
{

    private $err = [
        0 => "ok",
        -40001 => "签名验证错误",
        -40002 => "xml解析失败",
        -40003 => "sha加密生成签名失败",
        -40004 => "encodingAesKey 非法",
        -40005 => "appid 校验错误",
        -40006 => "aes 加密失败",
        -40007 => "aes 解密失败",
        -40008 => "解密后得到的buffer非法",
        -40009 => "base64加密失败",
        -40010 => "base64解密失败",
        -40011 => "生成xml失败",
    ];

    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct($this->err[$code], $code, $previous);
    }
}