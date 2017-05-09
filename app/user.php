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

        $email = strtolower($email);

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

        $email = strtolower($email);

        $account = isset($this->req['account']) ? $this->req['account'] : '';
        if (empty($account)) {
            G::tpl_msg($this->smarty, '淘宝账号不能为空', 'user_register.php');
            return;
        }

        $src = (isset($this->req['src']) && $this->req['src'] != '') ? $this->req['src'] : 'jebsen';
        if ($src != 'jebsen' && isset($this->src_array[$src])) {
            $src_info = $this->src_array[$src];
            $email_suffix = $src_info['email'];

            // jebsen_new 不检查邮箱后缀
            if ($src != 'jebsen_new' && strpos($email, $email_suffix) === false) {
                G::tpl_msg($this->smarty, '非法邮箱,请检查注册邮箱后缀', 'user_register.php', $src);
                return;
            }
        } else {
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

        $sql = "insert into user (`email`,`tb_account`,`src`,`create_time`) values (" . $this->pdo_db->quote($email) . "," . $this->pdo_db->quote($account) . "," . $this->pdo_db->quote($src) . ",NOW())";
        $n = $this->pdo_db->exec($sql);

        $key = G::authcode($email, 'ENCODE', EMAIL_KEY, 0);
        $url = DOMAIN . 'email_active.php?abc=' . urlencode($key);

//        G::sendEmail($email, $url);
        // 加入邮件待发池
        $insert_todo_sql = "insert into  todo (`email`,`url`,`tb_account`,`create_time`) values (" . $this->pdo_db->quote($email) . "," . $this->pdo_db->quote($url) . "," . $this->pdo_db->quote($account) . ",NOW())";
        $todo_n = $this->pdo_db->exec($insert_todo_sql);

        if ($src == 'jebsen_new') {
            $content_url = "https://jebsen.tmall.com/category.htm?spm=a1z10.5-b-s.w4011-16314359632.1.7pO7uQ&keyword=dyson";
            $content_qr_code = "http://jselect.online/login_t/reg/etc/img/qr_code_jebsen_new.png";
        } else {
            $content_url = "https://detail.tmall.com/item.htm?spm=a220z.1000880.0.0.Ub0RXh&id=542635015181";
            $content_qr_code = "http://jselect.online/login_t/reg/etc/img/qr_code.png";
        }


        $this->smarty->assign('content_url', $content_url);
        $this->smarty->assign('content_qr_code', $content_qr_code);
        $this->smarty->assign('status', 1);
        $this->smarty->assign('account', $account);
        $this->smarty->assign('email', $email);
        $this->smarty->display('send_email.php');
    }

    public function showBySrc()
    {

        $src = isset($this->req['src']) ? $this->req['src'] : 'jebsen';
        $day = isset($this->req['day']) ? $this->req['day'] : '';
        $status = isset($this->req['status']) ? intval($this->req['status']) : 3;

        if ($src == 'jebsen') {
            $sql = 'select * from user where (src = ' . $this->pdo_db->quote($src) . ' or src = \'jebsen_new\')';
        } else {
            $sql = 'select * from user where src = ' . $this->pdo_db->quote($src);
        }
        if ($day != '') $sql .= ' and left(create_time,10)=' . $this->pdo_db->quote($day);
        if ($status != 3) $sql .= ' and status=' . $this->pdo_db->quote($status);
        $rows = $this->pdo_db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $s = '';
        foreach ($rows as $row) {
            $s .= $row['tb_account'] . "\n";
        }
//        $s = substr($s,1);

        $msg = $s == '' ? '暂无该分类记录' : '';

        $src_list = array_keys($this->src_array);
        array_unshift($src_list, 'jebsen');

        $status_sql = '';
        if($status != 3)$status_sql = ' and status = '.$this->pdo_db->quote($status);
        
        if ($src == 'jebsen') {
            $day_in_db_sql = 'select left(create_time,10) as dd from user where (src=' . $this->pdo_db->quote($src) . ' or src = \'jebsen_new\') $status_sql group by dd order by dd desc limit 10';
        } else {
            $day_in_db_sql = 'select left(create_time,10) as dd from user where src=' . $this->pdo_db->quote($src) . ' $status_sql group by dd order by dd desc limit 10';
        }
        $days_in_db = $this->pdo_db->query($day_in_db_sql)->fetchAll(PDO::FETCH_ASSOC);

        $days_in_db_array = array();
        foreach ($days_in_db as $k => $row) {
            $days_in_db_array[$k] = $row['dd'];
        }

        $days = array();
        for ($i = 9; $i >= 0; $i--) {
            $day = date('Y-m-d', strtotime('-' . $i . ' day'));
            if (!in_array($day, $days_in_db_array)) continue;
            $url = 'index.php?do=user.showBySrc&src=' . $src . '&day=' . $day;
            $days[$day] = $url;
        }

        $this->smarty->assign('s', $s);
        $this->smarty->assign('days', $days);
        $this->smarty->assign('status', $status);
        $this->smarty->assign('src', $src);
        $this->smarty->assign('src_list', $src_list);
        $this->smarty->assign('msg', $msg);
        $this->smarty->display('tb_account.php');
    }
}