<?php

/**
 * Created by PhpStorm.
 * User: deanhu
 * Date: 2017/1/24
 * Time: 14:42
 */
class user extends page
{

    function showModify()
    {
        $this->smarty->display('user_modify.php');
    }

    function showRegister()
    {
        $this->smarty->display('user_register.php');
    }

    function modifyAccount()
    {
        $account = isset($this->req['account']) ? $this->req['account'] : '';
        if (empty($account)) {
            G::tpl_msg($this->smarty, '淘宝账号不能为空', 'user_modify.php');
            return;
        }

        $email = isset($this->req['email']) ? $this->req['email'] : '';
        if (empty($email)) {
            G::tpl_msg($this->smarty, '邮箱不能为空', 'user_modify.php');
            return;
        }

        $sql = "select * from user where email=" . $this->pdo_db->quote($email) . " limit 1";
        $user_row = $this->pdo_db->query($sql)->fetch(PDO::FETCH_ASSOC);
        if (!isset($user_row['email'])) {
            G::tpl_msg($this->smarty, '该邮箱并未注册账号', 'user_modify.php');
            return;
        }

        $update_sql = "update user set tb_account = " . $this->pdo_db->quote($account) . " where email = " . $this->pdo_db->quote($email) . " limit 1";
        $n = $this->pdo_db->exec($update_sql);

        $this->smarty->assign('modify_status', 1);
        $this->smarty->assign('email', $email);
        $this->smarty->assign('account', $account);
        $this->smarty->display('dashboard.php');
    }

    function register()
    {
        $email = isset($this->req['email']) ? $this->req['email'] : '';
        if (empty($email)) {
            G::tpl_msg($this->smarty, '邮箱不能为空', 'user_register.php');
            return;
        }

        $account = isset($this->req['account']) ? $this->req['account'] : '';
        if (empty($account)) {
            G::tpl_msg($this->smarty, '淘宝账号不能为空', 'user_register.php');
            return;
        }
        
        $src = (isset($this->req['src']) && $this->req['src'] != '') ? $this->req['src'] : 'jebsen';
        if($src!='jebsen' && isset($this->src_array[$src])){
            $src_info = $this->src_array[$src];
            $email_suffix = $src_info['email'];

            if(strpos($email,$email_suffix)===false){
                G::tpl_msg($this->smarty, '非法邮箱,请检查注册邮箱后缀', 'user_register.php', $src);
                return;
            }
        }else{
            $src = 'jebsen';
        }

        $sql = "select * from user where email=" . $this->pdo_db->quote($email) . " limit 1";
        $user_row = $this->pdo_db->query($sql)->fetch(PDO::FETCH_ASSOC);
        if (isset($user_row['email'])) {
            G::tpl_msg($this->smarty, '该邮箱已经被注册过,请注意检查是否拼写错误.Email:' . $email, 'user_register.php');
            return;
        }

        $sql = "select * from user where tb_account=" . $this->pdo_db->quote($account) . " limit 1";
        $user_row = $this->pdo_db->query($sql)->fetch(PDO::FETCH_ASSOC);
        if (isset($user_row['tb_account'])) {
            G::tpl_msg($this->smarty, '该淘宝账号已经被注册,请注意检查是否拼写错误.Account:' . $account, 'user_register.php');
            return;
        }

        $sql = "insert into user (`email`,`tb_account`,`create_time`) values (" . $this->pdo_db->quote($email) . "," . $this->pdo_db->quote($account) . ",NOW())";
        $n = $this->pdo_db->exec($sql);

        $key = G::authcode($email, 'ENCODE', EMAIL_KEY, 0);
        $url = DOMAIN . 'email_active.php?abc=' . urlencode($key);

        G::sendEmail($email, $url);

        $this->smarty->assign('status', 1);
        $this->smarty->assign('account', $account);
        $this->smarty->assign('email', $email);
        $this->smarty->display('send_email.php');
    }

    public function showBySrc(){

        $src = isset($this->req['src'])?$this->req['src']:'jebsen';

        $sql = 'select * from user where src = '.$this->pdo_db->quote($src);
        $rows = $this->pdo_db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $s = '';
        foreach ($rows as $row){
            $s .= ','.$row['tb_account'];
        }
        $s = substr($s,1);

        $msg = $s==''?'暂无该分类记录':'';
        
        $src_list = array_keys($this->src_array);
        array_unshift($src_list, 'jebsen');
        
        $this->smarty->assign('s',$s);
        $this->smarty->assign('src',$src);
        $this->smarty->assign('src_list',$src_list);
        $this->smarty->assign('msg',$msg);
        $this->smarty->display('tb_account.php');
    }
}