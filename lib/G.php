<?php

class G
{
    /*HTTP验证*/
    public static function http_auth()
    {
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="Action Auth"');
            header('HTTP/1.0 401 Unauthorized');
            G::code_die(401, "ACTION_NEED_AUTH");
        } else {
            if ($_SERVER['PHP_AUTH_USER'] != PHP_AUTH_USER || $_SERVER['PHP_AUTH_PW'] != PHP_AUTH_PW) {
                header('WWW-Authenticate: Basic realm="Action Auth"');
                header('HTTP/1.0 401 Unauthorized');
                G::code_die(401, "ACTION_NEED_AUTH");
            }
        }
        return true;
    }

    public static function code_die($code, $result)
    {
        $_get = $_REQUEST;
        $o = array("code" => $code, "request" => $_get, "result" => $result);
        die(json_encode($o));
    }

    public static function tpl_msg($smarty, $msg, $html, $param='')
    {
        if($param!=''){
            $smarty->assign('src', $param);
        }
        $smarty->assign('msg', $msg);
        $smarty->assign('status', 1);
        $smarty->display($html);
    }

    /*
    public static function sendEmail($email, $url)
    {
        $send_email = "jselect@qq.com";
        $send_pwd = "jselect!23";

        $body = "<html>
                    <head>   
                    <meta http-equiv=\"Content-Language\" content=\"zh-cn\">   
                    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=GB2312\">   
                    </head>   
                    <body>   
                        <p>请您点击以下链接,以完成系统账号激活操作!</p>
                        <p><a href='$url' target='_blank'>点击激活</a></p>
                    </body>   
                    </html>";

        $smtpserver = "smtp.qq.com";//SMTP服务器
        $smtpserverport = 25;//SMTP服务器端口
        $smtpusermail = $send_email;//SMTP服务器的用户邮箱
        $smtpemailto = $email;//发送给谁
        $smtpuser = $send_email;//SMTP服务器的用户帐号
        $smtppass = $send_pwd;//SMTP服务器的用户密码
        $mailsubject = "XXX系统账号激活邮件";//邮件主题
        $mailbody = $body;//邮件内容
        $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件

        $smtp = new smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp->debug = true;//是否显示发送的调试信息
        $smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
        return true;
    }
    */

    public static function sendEmail($email, $url, $title_suffix='')
    {
//        $send_pwd = "jselect!23";
//        $send_email = "jselect@qq.com";
//        $send_pwd = "fqxqxftirechdbhb";

//        $send_email = 'jselect@qq.com';
//        $send_pwd = 'fqxqxftirechdbhb';
//        $send_stmp_server = 'smtp.qq.com';

//        $send_email = 'jselect@aliyun.com';
//        $send_pwd = 'jselect!23';
//        $send_stmp_server = 'smtp.aliyun.com';

//        $send_email = 'jselect@163.com';
//        $send_pwd = 'jselect000';
//        $send_stmp_server = 'smtp.163.com';

        $send_email = 'noreply_jselect@jebsen.com';
        $send_pwd = 'Pozo6754';
        $send_stmp_server = 'smtp.office365.com';
//        $send_port = 465;
        $send_port = 587;

        $body = "<html>
                    <head>   
                    <meta http-equiv=\"Content-Language\" content=\"zh-cn\">   
                    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=GB2312\">   
                    </head>   
                    <body>   
                        <p>请您点击以下链接,以便完成系统账号激活操作!</p>
                        <p><a href='$url' target='_blank'>点击激活</a></p><br/>
                        <p><a href='https://detail.tmall.com/item.htm?spm=a220z.1000880.0.0.Ub0RXh&id=542635015181'>https://detail.tmall.com/item.htm?spm=a220z.1000880.0.0.Ub0RXh&id=542635015181</a></p>
                        <p><img src='http://jselect.online/login_t/reg/etc/img/qr_code.png'></p>
                    </body>   
                    </html>";

        //示例化PHPMailer核心类
        $mail = new PHPMailer();

        //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
//        $mail->SMTPDebug = 2;

        //使用smtp鉴权方式发送邮件，当然你可以选择pop方式 sendmail方式等 本文不做详解
        //可以参考http://phpmailer.github.io/PHPMailer/当中的详细介绍
        $mail->isSMTP();
        //smtp需要鉴权 这个必须是true
        $mail->SMTPAuth = true;
        //链接qq域名邮箱的服务器地址
        $mail->Host = gethostbyname($send_stmp_server);
        //设置使用ssl加密方式登录鉴权
//        $mail->SMTPSecure = 'ssl';
        $mail->SMTPSecure = 'tls';
        //设置ssl连接smtp服务器的远程服务器端口号 可选465或587
        $mail->Port = $send_port;
        //设置smtp的helo消息头 这个可有可无 内容任意
//        $mail->Helo = "Hello Server";
        //设置发件人的主机域 可有可无 默认为localhost 内容任意，建议使用你的域名
        $mail->Hostname = 'localhost';
        //设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
        $mail->CharSet = 'UTF-8';
        //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
//        $mail->FromName = 'Jselect企业购员工注册系统邮箱';
        $mail->setFrom($send_email, 'Jselect企业购员工注册系统邮箱');
        //smtp登录的账号 这里填入字符串格式的qq号即可
        $mail->Username = $send_email;
        //smtp登录的密码 这里填入“独立密码” 若为设置“独立密码”则填入登录qq的密码 建议设置“独立密码”
        $mail->Password = $send_pwd;
        //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
//        $mail->From = $send_email;
        //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
        $mail->isHTML(true);
        //设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
        $mail->addAddress($email);
        //添加该邮件的主题
        $mail->Subject = 'Jselect企业购员工注册系统账号激活邮件_'.$title_suffix;
        //添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
        $mail->Body = $body;

        //发送命令 返回布尔值
        //PS：经过测试，要是收件人不存在，若不出现错误依然返回true 也就是说在发送之前 自己需要些方法实现检测该邮箱是否真实有效
        $status = $mail->send();

        //简单的判断与提示信息
        if ($status) {
            return true;
        } else {
            file_put_contents('/tmp/email_log.txt', date('Ymd H:i:s')."\t发送邮件失败，错误信息：' . $mail->ErrorInfo.\n", FILE_APPEND);
            return false;
        }
    }

    public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙   
        $ckey_length = 4;

        // 密匙   
        $key = md5($key ? $key : $GLOBALS['discuz_auth_key']);

        // 密匙a会参与加解密   
        $keya = md5(substr($key, 0, 16));
        // 密匙b会用来做数据完整性验证   
        $keyb = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文   
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) :
            substr(md5(microtime()), -$ckey_length)) : '';
        // 参与运算的密匙   
        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)， 
        // 解密时会通过这个密匙验证数据完整性   
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确   
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) :
            sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        // 产生密匙簿   
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度   
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // 核心加解密部分   
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符   
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'DECODE') {
            // 验证数据有效性，请看未加密明文的格式   
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) &&
                substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)
            ) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因   
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码   
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }

}