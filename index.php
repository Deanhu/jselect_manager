<?php
/**
 * Created by PhpStorm.
 * User: deanhu
 * Date: 2017/1/20
 * Time: 16:58
 */

// 引入文件
require_once dirname(__File__).'/etc/define.php';
//require_once dirname(__File__).'/lib/PHPMailer/class.phpmailer.php';
//require_once dirname(__File__).'/lib/PHPMailer/class.smtp.php';
require_once dirname(__File__).'/lib/PHPMailer/PHPMailerAutoload.php';
require_once dirname(__File__).'/lib/G.php';
require_once dirname(__File__).'/lib/Smarty.class.php';
require_once dirname(__File__).'/app/page.php';

// 访问目录验证


// 功能跳转

$uri = $_SERVER ['REQUEST_URI'];
$do = isset($_REQUEST ['do'])?$_REQUEST['do']:'login.show';
$p = explode ( '.', $do );
$path ['controller'] = $p [0];
$path ['action'] = $p [1];

if (! isset ( $path ['controller'] ))
    $path ['controller'] = "home";
if (! isset ( $path ['action'] ))
    $path ['action'] = "p";
define ( 'CONTROLLER', $path ['controller'] );
define ( 'ACTION', $path ['action'] );

if ($path ['controller'] == 'homepage')
    G::code_die ( 404, '无效地址' );
if ($path ['controller'] == 'admin')
    G::code_die ( 404, '无效地址' );

// 实例化程序
$filename = ROOT . "/app/{$path['controller']}.php";
if (! file_exists ( $filename ))
    G::code_die ( 404, '无效调用' );

require_once $filename;
$page = new $path ['controller'] ();
$page->{$path ['action']} ();