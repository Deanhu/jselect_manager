<?php

/**
 * Created by PhpStorm.
 * User: deanhu
 * Date: 2017/1/24
 * Time: 16:53
 */
class email extends page
{

    function sendEmail()
    {
        $email = isset($this->req['email']) ? $this->req['email'] : '';
        if (empty($email)) {
            echo json_encode(array('status' => 0, 'msg' => '邮箱不能为空'));
            exit;
        }
        $email = strtolower($email);
        
        $sql = "select * from user where email=" . $this->pdo_db->quote($email) . " limit 1";
        $user_row = $this->pdo_db->query($sql)->fetch(PDO::FETCH_ASSOC);
        if (!isset($user_row['email'])) {
            echo json_encode(array('status' => 0, 'msg' => '该邮箱还未注册为本系统用户'));
            exit;
        }

        
        $key = G::authcode($email, 'ENCODE', EMAIL_KEY, 0);
        $url = DOMAIN . 'email_active.php?abc=' . urlencode($key);

        G::sendEmail($email, $url);

        echo json_encode(array('status' => 1, 'msg' => '邮件发送成功!'));
        exit;
    }
}