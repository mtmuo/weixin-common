<?php


namespace bajiu\weixin\common\request;


class Request
{
    public static function get($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($curl);
        $header = curl_getinfo($curl);
        curl_close($curl);
        return new Response($header, $response);
    }

    public static function post($url, $body, array $header = ['Content-type: application/json'], $sslCert = false)
    {
        if (in_array("Content-type: application/json", $header) && is_array($body)) {
            $body = json_encode($body, JSON_UNESCAPED_UNICODE);
        } else if (in_array("Content-type: application/xml", $header) && is_array($body) && count($body) > 0) {
            $xml = "<xml>";
            foreach ($body as $key => $val) {
                if (is_numeric($val)) {
                    $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
                } else {
                    $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
                }
            }
            $xml .= "</xml>";
            $body = $xml;
        } else {
            is_array($body) && ($body = http_build_query($body));
        }

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        curl_setopt($curl, CURLOPT_HEADER, false);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($curl, CURLOPT_POST, TRUE);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

        $response = curl_exec($curl);
        $header = curl_getinfo($curl);
        curl_close($curl);

        return new Response($header, $response);
    }
}