<?php
/**
 * Created by PhpStorm.
 * User: ieso
 * Date: 2019/1/24
 * Time: 9:30
 */

namespace App\Admin\Controllers;


use App\Components\QRManager;
use App\Models\Agent;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class QRController
{
    private static function getAgentXCXQR($scene, $page =null)
    {
        if($page==null){
            $page='pages/index/index';
        }
        $time =time();
//			date("Y-m-d_h:i:s");
        $filename = "{$scene}_$time";
        $access_token = QRManager::getACCESS_TOKEN()->access_token;
        if (!$access_token)
            return "失败";
        $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token;
        $headers = array('Content-type: ' . 'application/json');
        $body = [
//			'access_token'=>$access_token,
            'scene' => $scene,
            'page' => $page,
        ];
        // 拼接字符串
        $fields_string = json_encode($body);

        $con = curl_init();
        curl_setopt($con, CURLOPT_URL, $url);
//		curl_setopt($con, CURLOPT_SSL_VERIFYHOST, FALSE);
//		curl_setopt($con, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($con, CURLOPT_HEADER, 0);
        curl_setopt($con, CURLOPT_POST, 1);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($con, CURLOPT_POSTFIELDS, $fields_string);
        $info = curl_exec($con);
        //发送HTTPs请求并获取返回的数据，推荐使用curl
//		$json = json_decode($info);//对json数据解码
        $err = curl_error($con);
        curl_close($con);

        $path = storage_path('app/public/createdQR');


        $filePath = "$path/{$filename}.jpg";
//		return $path;
//		$i = 0;
//		do
//			str_replace('/', '\\', $filePath, $i);
//		while ($i >= 0);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        file_put_contents($filePath, $info);
//		$url = qiniu_upload($filePath, 'wxqr');  //调用的全局函数
//		unlink($filePath);
        $app_url=env("APP_URL");
        return "$app_url/storage/createdQR/{$filename}.jpg";
//		dd($info);
    }

    public function getForm(Content $content,Request $request){

        return $content
            ->header('生成二维码')
            ->description('')
            ->row(function (Row $row)use($request){
                $form=new Box("二维码信息",view("admin.getQR",
                    [
                        'sence'=>$request->get("sence"),
                        'page'=>$request->get("page")
                    ]));
                $row->column("8",$form);
                if ($request->filled("sence")||$request->filled("page")){
                    $image=self::getAgentXCXQR($request->get("sence"),$request->get("page"));
                    $row->column(4,new Box("二维码","<image src='$image'></image>"));
                }
            });
    }
}