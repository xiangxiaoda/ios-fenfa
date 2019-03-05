<?php
error_reporting(0);
//取得编码，金额，用户ID，时间戳
header("Content-type: text/html; charset=utf-8");
$type = $_REQUEST['zftype'];
$pay_bankcode = "903";   //银行编码
$pay_amount = "0.00";    //交易金额
$title = "支付";
$showContent = "";
if($type == "wxh5"){
	//用可自定义金额
	$pay_bankcode = 901;
	$pay_amount = "0.00";
	$title = "龙凤娱乐微信H5支付";
	$showContent = "微信支付:自定义金额";
}else if($type == "wxma"){
	//微信扫码
	$pay_bankcode = 902;//必须有金额
	$pay_amount = $_REQUEST['amount'];
	if($pay_amount == 0){
		echo "支付金额不能为0";
		exit;
	}
	$title = "龙凤娱乐微信扫码支付";
	$showContent = "微信扫码支付:" . $pay_amount . "元";
}else if($type == "zfbma"){
	//支付宝扫码
	$pay_bankcode = 903;//必须有金额
	$pay_amount = $_REQUEST['amount'];
	if($pay_amount == 0){
		echo "支付金额不能为0";
		exit;
	}
	$title = "龙凤娱乐支付宝扫码支付";
	$showContent = "支付宝扫码支付:" . $pay_amount . "元";
}else{
	echo "无效的支付通道";
	exit;
}
$user_id = $_REQUEST['uid'];
if($user_id == 0){
	echo "找不到用户id";
	exit;
}
/*扫码支付(金额：<?php echo $pay_amount; ?>元);*/
$time = $_REQUEST['time'];
$pay_memberid = "10051";   //商户ID
$pay_orderid = 'E'.date("YmdHis").rand(100000,999999);    //订单号
$pay_attach = $user_id . "_" . $time;
$pay_applydate = date("Y-m-d H:i:s");  //订单时间
$pay_notifyurl = "http://39.107.205.90:8888/hepay/server.php";   //服务端返回地址
$pay_callbackurl = "http://39.107.205.90:8888/hepay/page.php";  //页面跳转返回地址
$Md5key = "u3kmae9hbhmos2ap2y529qa6xjd5iu0l";   //密钥
$tjurl = "http://www.payjfpal.com/Pay_Index.html";   //提交地址
//扫码
$native = array(
    "pay_memberid" => $pay_memberid,
    "pay_orderid" => $pay_orderid,
    "pay_amount" => $pay_amount,
    "pay_applydate" => $pay_applydate,
    "pay_bankcode" => $pay_bankcode,
    "pay_notifyurl" => $pay_notifyurl,
    "pay_callbackurl" => $pay_callbackurl
);
ksort($native);
$md5str = "";
foreach ($native as $key => $val) {
    $md5str = $md5str . $key . "=" . $val . "&";
}
//echo($md5str . "key=" . $Md5key);
$sign = strtoupper(md5($md5str . "key=" . $Md5key));
$native["pay_md5sign"] = $sign;
$native['pay_attach'] = $pay_attach;
$native['pay_productname'] ='龙凤娱乐充值';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>$title</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <div class="row" style="margin:15px;0;">
        <div class="col-md-12">
            <form class="form-inline" method="post" action="<?php echo $tjurl; ?>">
                <?php
                foreach ($native as $key => $val) {
                    echo '<input type="hidden" name="' . $key . '" value="' . $val . '">';
                }
                ?>
                <button type="submit" class="btn btn-success btn-lg"><?php echo $showContent; ?></button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
</body>
</html>