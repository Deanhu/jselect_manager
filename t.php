<?php
/**
 * Created by PhpStorm.
 * User: deanhu
 * Date: 2017/4/13
 * Time: 20:44
 */

require_once dirname(__FILE__) . '/etc/define.php';
require_once ROOT . '/lib/G.php';

list($mysql_host, $mysql_port) = explode(':', MYSQL_HOST);
$pdo_db = new PDO("mysql:host=" . $mysql_host . ";dbname=" . MYSQL_DBNAME, MYSQL_USERNAME, MYSQL_PWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

$select_sql = "select * from user where status = 0 limit 0,500";
$user_rows = $pdo_db->query($select_sql)->fetchAll(PDO::FETCH_ASSOC);

$n = 0;
foreach ($user_rows as $row) {
    $email = $row['email'];
    $tb_account = $row['tb_account'];

    $key = G::authcode($email, 'ENCODE', EMAIL_KEY, 0);
    $url = DOMAIN . 'email_active.php?abc=' . urlencode($key);

    $insert_sql = "replace into todo (`email`,`url`,`tb_account`,`create_time`) values (" . $pdo_db->quote($email) . "," . $pdo_db->quote($url) . "," . $pdo_db->quote($tb_account) . ",NOW())";
    $i = $pdo_db->exec($insert_sql);
    $n += $i;
}

echo 'Done. Num:'.$n;










