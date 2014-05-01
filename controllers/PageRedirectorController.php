<?php // -*- encoding utf-8 -*-

class PageRedirectorController
{

    private static $tt_host = '';
    private static $tt_port = '';
    private static $front_url = '';

    public function __construct($env = 'production')
    {
        $conf = parse_ini_file(__DIR__ . '/../conf/urlmask.ini', true);
        if(isset($conf[$env]['tt_host'], $conf[$env]['tt_port'], $conf[$env]['front_url']) === true)
        {
            self::$tt_host = $conf[$env]['tt_host'];
            self::$tt_port = $conf[$env]['tt_port'];
            self::$front_url = $conf[$env]['front_url'];
        }
    }

    public function execute()
    {
        $hash_url = '';
        if(empty($_GET['url']) === false)
        {
            $hash_url = $_GET['url'];
        }
        $tt = new TokyoTyrantConnector(self::$tt_host, self::$tt_port);
        $raw_url = $tt->getValue($hash_url);
        if(is_null($raw_url) === false)
        {
            header('HTTP/1.1 301');
            header($raw_url);
        }
        else
        {
            header('HTTP/1.1 404');
            header(self::$front_url . '/404');
        }
    }
}
