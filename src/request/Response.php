<?php


namespace bajiu\weixin\common\request;

use bajiu\weixin\common\exception\RequestException;
use bajiu\weixin\common\exception\WeiXinException;

class Response
{
    /**
     * 请求详情头部
     * @var
     */
    public $header;

    /**
     * 原始相应数据
     * @var
     */
    public $original;


    /**
     * 初始化数据
     * Response constructor.
     * @param $header
     * @param $response
     * @throws RequestException
     */
    public function __construct($header, $response)
    {
        if ($header["http_code"] !== 200 || empty($response)) {
            throw new RequestException("请求微{$header["url"]}失败,请稍后再试...", 500);
        }
        $this->original = $response;
        $this->header = $header;

    }

    /**
     * @return array
     * @throws WeChatException
     */
    public function toArray()
    {

    }

    /**
     * json格式化返回
     * @return array
     * @throws WeiXinException
     */
    public function json(): array
    {
        $data = json_decode($this->original, true);
        if (!is_array($data) || empty($data)) {
            throw new WeiXinException("解析数据错误，原始错误：{$this->original}", 43001);
        }
        if (isset($data["errcode"]) && $data["errcode"]) {
            throw new WeiXinException("请求参数错误，错误信息：{$data['errmsg']}", 43002);
        }
        return $data;
    }

    /**
     * xml格式化返回
     * @return array
     * @throws WeiXinException
     */
    public function xml(): array
    {
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($this->original, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        if (!is_array($data) || empty($data)) {
            throw new WeiXinException("解析数据错误，原始错误：{$this->original}", 43003);
        }
        if (isset($data["errcode"]) && $data["errcode"]) {
            throw new WeiXinException("请求参数错误，错误信息：{$data['errmsg']}", 43004);
        }
        return $data;
    }

    /**
     * 二进制文件返回
     * @return string
     * @throws WeiXinException
     */
    public function binary(): string
    {
        $data = json_decode($this->original, true);
        if (is_array($data)) {
            throw new WeiXinException("请求响应错误，原始错误：{$data['errmsg']}", 43005);
        }
        return $this->original;
    }
}