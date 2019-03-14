<?php
/**
 * Created by PhpStorm.
 * 物流manager
 * User: Acer
 * Date: 2018/12/10
 * Time: 17:38
 */

namespace App\Components;


use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Support\Facades\Log;

class WuliuManager
{
    public function __construct()
    {
        //电商ID。请到快递鸟官网申请http://kdniao.com/reg
        $this->EBusinessID = "test1426376";
        //电商加密私钥，快递鸟提供，注意保管，不要泄漏。请到快递鸟官网申请http://kdniao.com/reg
        $this->AppKey = 'd2b509d5-f570-4825-aed2-88d824a48e69';
        //请求url
        $this->ReqURL ='http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx';
//        $this->ReqURL = "http://sandboxapi.kdniao.com:8080/kdniaosandbox/gateway/exterfaceInvoke.json";
    }

    /**
     * 查询接口
     * @param $order_sn
     * 订单编号
     * @param $shipper_code
     * 快递公司编码
     * @param $logistic_code
     * 物流单号
     */
    public function query($order_sn, $shipper_code, $logistic_code)
    {
        $logisticResult = $this->getOrderTracesByJson($order_sn, $shipper_code, $logistic_code);
        return json_decode($logisticResult);
    }

    /**
     * Json方式 查询订单物流轨迹
     */
    public function getOrderTracesByJson($order_sn, $shipper_code, $logistic_code)
    {
        $requestData = json_encode(["OrderCode"=>$order_sn,
            "ShipperCode"=>$shipper_code,
            "LogisticCode"=>$logistic_code]);

//            "{'OrderCode':'" . $order_sn . "','ShipperCode':'" . $shipper_code . "','LogisticCode':'" . $logistic_code . "'}";

        $datas = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData),
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->AppKey);
        Log::info("查询物流发送：".json_encode($datas));
        $result = $this->sendPost($this->ReqURL, $datas);

        //根据公司业务处理返回的信息......

        return $result;
    }

    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    public function sendPost($url, $datas)
    {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if (empty($url_info['port'])) {
            $url_info['port'] = 80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader .= "Host:" . $url_info['host'] . "\r\n";
        $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader .= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader .= "Connection:close\r\n\r\n";
        $httpheader .= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets .= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param data
     * 内容
     * @param appkey
     * Appkey
     * @return DataSign
     * 签名
     */
    public function encrypt($data, $appkey)
    {
        return urlencode(base64_encode(md5($data . $appkey)));
    }

}