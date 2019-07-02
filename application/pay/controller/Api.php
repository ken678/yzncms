<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 支付API接口控制器
// +----------------------------------------------------------------------
namespace app\pay\controller;

use app\common\controller\Homebase;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use think\facade\Session;
use think\Response;

class Api extends HomeBase
{
    /**
     * 微信支付
     * @return string
     */
    public function wechat()
    {
        $isWechat = stripos($this->request->server('HTTP_USER_AGENT'), 'MicroMessenger') !== false;
        if ($isWechat) {

        } else {
            //发起PC支付(Native支付)
            $orderData = Session::get("wechatorderdata");
            $data = [
                'body' => $orderData['body'],
                'code_url' => $orderData['code_url'],
                'out_trade_no' => $orderData['out_trade_no'],
                'return_url' => $orderData['return_url'],
                'total_fee' => $orderData['total_fee'],
            ];
            $this->assign("data", $data);
        }
        return $this->fetch('/wechat');
    }

    /**
     * 生成二维码
     * @return Response
     */
    public function qrcode()
    {
        $text = $this->request->get('text', 'hello world');
        $qrCode = new QrCode($text);
        $rs = $qrCode
            ->setWriterByName('png')
            ->setMargin(10)
            ->setEncoding('UTF-8')
            ->setSize(250)
            /*->setLogoPath(__DIR__ . '/../assets/symfony.png')
        ->setLogoWidth(150)*/
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH) //纠错级别
            ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0])
            ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])
            ->setValidateResult(false);
        return new Response($qrCode->writeString(), 200, ['Content-Type' => $qrCode->getContentType()]);
    }

}
