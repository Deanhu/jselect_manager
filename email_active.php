<?php
/**
 * Created by PhpStorm.
 * User: deanhu
 * Date: 2017/1/24
 * Time: 17:09
 */

require_once dirname(__File__) . '/etc/define.php';
require_once dirname(__File__) . '/lib/G.php';


$abc = isset($_GET['abc']) ? $_GET['abc'] : '';
if (empty($abc)) die('无效URL');

$check_email = G::authcode($abc, 'DECODE', EMAIL_KEY, 0);

$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
if (!preg_match($pattern, $check_email)) die('不合法的URL');

// init PDO
list($mysql_host, $mysql_port) = explode(':', MYSQL_HOST);
$pdo_db = new PDO("mysql:host=" . $mysql_host . ";dbname=" . MYSQL_DBNAME, MYSQL_USERNAME, MYSQL_PWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

$sql = "select * from user where email=" . $pdo_db->quote($check_email) . " limit 1";
$user_row = $pdo_db->query($sql)->fetch(PDO::FETCH_ASSOC);
if (!isset($user_row['email'])) die('Are you sure?');

$update_sql = "update user set status = 1 where email = " . $pdo_db->quote($check_email) . " limit 1";
$n = $pdo_db->exec($update_sql);

if($n){
    echo '恭喜你激活成功';
    header("location: ".DOMAIN.'index.php?ea=1&do=login.loginEmail&email='.$check_email);
    exit;
}else{
    die('您已经激活过了~');
}
