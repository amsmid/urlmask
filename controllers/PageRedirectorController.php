<?php // -*- encoding utf-8 -*-

class PageRedirectorController
{

    private static $api_url = '';
    private static $front_url = '';

    public function __construct($env = 'production')
    {
        $conf = parse_ini_file(__DIR__ . '/../conf/urlmask.ini', true);
        if(isset($conf[$env]['api_url'], $conf[$env]['front_url']) === true)
        {
            self::$api_url = $conf[$env]['api_url'];
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
        $result = json_decode(file_get_contents(self::$api_url . "/raw_url.php?url=$hash_url"));
        if(empty($result->raw_url) === false)
        {
            header('HTTP/1.1 301');
            header('Location: ' . $result->raw_url);
        }
        else
        {
            header('HTTP/1.1 404');
            header('Location: ' . self::$front_url . '/404');
        }
    }
}
