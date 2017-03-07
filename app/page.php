<?php

class page
{
    protected $req;

    protected $pdo_db;
    protected $smarty;
    protected $redis;
    protected $redis_status;
    protected $user;
    protected $src_array;

    public function __construct()
    {
        $this->req = $_REQUEST;

        // init PDO
        list($mysql_host, $mysql_port) = explode(':', MYSQL_HOST);
        $this->pdo_db = new PDO("mysql:host=" . $mysql_host . ";dbname=" . MYSQL_DBNAME, MYSQL_USERNAME, MYSQL_PWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

        // init redis
//        $this->redis = new Redis();
//        $this->redis->connect(REDIS_CACHE_HOST, REDIS_CACHE_PORT, 300);
//        $this->redis_status = $this->redis->ping();

        // init Smarty
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir(ROOT . '/tpl/');
        $this->smarty->setCompileDir(ROOT . '/tpl_c/');
        $this->smarty->setConfigDir(ROOT . '/etc/');
        $this->smarty->setCacheDir(ROOT . '/tpl_c/cache/');
        $this->smarty->caching = false;
        $this->smarty->left_delimiter = '{';
        $this->smarty->right_delimiter = '}';

        if(file_exists(ROOT.'/src.json')){
            $this->src_array = json_decode(file_get_contents(ROOT.'/src.json'),true);
        }else{
            $this->src_array = json_decode('{"icbc":{"name":"\u4e2d\u56fd\u5de5\u5546\u94f6\u884c","email":"@icbc.com","url":"www.icbc.com"},"hsbc":{"name":"\u6c47\u4e30\u94f6\u884c","email":"@hsbc.com","url":"www.hsbc.com"}}',true);
        }

    }

}