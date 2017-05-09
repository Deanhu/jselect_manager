<?php
/**
 * Created by PhpStorm.
 * User: deanhu
 * Date: 2017/4/24
 * Time: 17:08
 */

require_once dirname(dirname(__FILE__)) . '/etc/define.php';

require_once ROOT . '/lib/G.php';
require_once ROOT . '/lib/PHPMailer/class.phpmailer.php';
require_once ROOT . '/lib/PHPMailer/class.smtp.php';

$year = date('Y');
$month = date('n');
$day = date('j');
$day_month_last = date('t');
$date = date('Y-m-d');
$time = date('Y-m-d H:i:s');
$unix_timestamp = time();
$hour = intval(date("H", $unix_timestamp));
$minute = intval(date("i", $unix_timestamp));

if (isset($argv[1]) && $argv[1] == 'test') {
    list($mysql_host, $mysql_port) = explode(':', MYSQL_HOST);
    $pdo_db = new PDO("mysql:host=" . $mysql_host . ";dbname=" . MYSQL_DBNAME, MYSQL_USERNAME, MYSQL_PWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

    // 检查todo表格中邮件内容,并发送
    // 检查数据库表中是否有未发邮件
    $select_sql = "select * from todo where status = 0 order by create_time asc limit 1";
    $todo_row = $pdo_db->query($select_sql)->fetch(PDO::FETCH_ASSOC);
    if (!isset($todo_row['email'])) {
        write_log('无待发邮件');
        exit;
    }

    $email = $todo_row['email'];
    $url = $todo_row['url'];
    $tb_account = $todo_row['tb_account'];

    $src_type = 1;
    $user_row = $pdo_db->query("select * from user where email = '$email' limit 1")->fetch(PDO::FETCH_ASSOC);
    if ($user_row['src'] == 'jebsen_new') {
        $src_type = 2;
    }

    $status = G::sendEmail($email, $url, $tb_account, $src_type);
    if ($status) {
        write_log('邮件发送成功.Email:' . $email);
        $update_sql = "update todo set `status`=1 where email = " . $pdo_db->quote($email) . " limit 1";
        $i = $pdo_db->exec($update_sql);
        if ($i > 0) write_log('修改状态成功.Email:' . $email);
    } else {
        write_log('邮件发送失败.Email:' . $email, 'ERROR');
    }
    exit;
}

/* 每3分钟 */
//if ($minute % 3 == 1) {
if (1) {

    list($mysql_host, $mysql_port) = explode(':', MYSQL_HOST);
    $pdo_db = new PDO("mysql:host=" . $mysql_host . ";dbname=" . MYSQL_DBNAME, MYSQL_USERNAME, MYSQL_PWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

    // 检查todo表格中邮件内容,并发送
    // 检查数据库表中是否有未发邮件
    $select_sql = "select * from todo where status = 0 order by create_time asc limit 1";
    $todo_row = $pdo_db->query($select_sql)->fetch(PDO::FETCH_ASSOC);
    if (!isset($todo_row['email'])) {
        write_log('无待发邮件');
        exit;
    }

    $email = $todo_row['email'];
    $url = $todo_row['url'];
    $tb_account = $todo_row['tb_account'];
    
    $src_type = 1;
    $user_row = $pdo_db->query("select * from user where email = '$email' limit 1")->fetch(PDO::FETCH_ASSOC);
    if ($user_row['src'] == 'jebsen_new') {
        $src_type = 2;
    }

    $status = G::sendEmail($email, $url, $tb_account, $src_type);
    if ($status) {
        write_log('邮件发送成功.Email:' . $email);
        $after_status = 1;
    } else {
        write_log('邮件发送失败.Email:' . $email, 'ERROR');
        $after_status = 3;
    }
    $update_sql = "update todo set `status`=$after_status where email = " . $pdo_db->quote($email) . " limit 1";
    $i = $pdo_db->exec($update_sql);
    if ($i > 0) write_log('修改状态成功.Email:' . $email);
    exit;
}

write_log('running!');

function write_log($msg, $type = 'NORMAL')
{
    $file_name = '/tmp/jselect_cron_log.txt';
    if (file_put_contents($file_name, '[' . date('Y-m-d H:i:s') . '] [' . $type . '] ' . $msg . "\n", FILE_APPEND | LOCK_EX)) return true;
}