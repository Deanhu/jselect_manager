<?php

/**
 * Created by PhpStorm.
 * User: deanhu
 * Date: 2017/1/20
 * Time: 17:18
 */
class login extends page
{

    function show()
    {
        if(isset($this->req['ea']) && $this->req['ea']==1){

            $this->smarty->assign('login_status',1);
            $this->smarty->assign('msg','恭喜您邮箱和淘宝账号已经绑定成功.');
        }
        $this->smarty->display('user_register.php');
    }

    function loginEmail()
    {
        $email = isset($this->req['email']) ? $this->req['email'] : '';
        if (empty($email)) {
            G::tpl_msg($this->smarty, '邮箱不能为空', 'user_register.php');
            return;
        }

        $sql = "select * from user where email=" . $this->pdo_db->quote($email) . " limit 1";
        $user_row = $this->pdo_db->query($sql)->fetch(PDO::FETCH_ASSOC);
        if (!isset($user_row['email'])) {
            G::tpl_msg($this->smarty, '该邮箱用户不存在', 'user_register.php');
            return;
        }

        // status = 0 跳转发送验证邮件页面
        if (intval($user_row['status']) == 0) {
            $this->smarty->assign('email', $email);
            $this->smarty->display('send_email.php');
            return;
        }

        if(isset($this->req['ea']) && $this->req['ea']==1){
            $this->smarty->assign('login_status',1);
            $this->smarty->assign('msg','恭喜您邮箱和淘宝账号已经绑定成功.');
        }

        $this->smarty->assign('email', $email);
        $this->smarty->assign('account', $user_row['tb_account']);
        $this->smarty->display('dashboard.php');
    }

}