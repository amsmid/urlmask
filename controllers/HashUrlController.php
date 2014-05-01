<?php // -*- encoding utf-8 -*-

class HashUrlController
{
    const MAX_URL_LENGTH = 200;
    private static $salt = '';
    private static $tt_host = '';
    private static $tt_port = '';
    private static $front_url = '';

    public function __construct($env = 'production')
    {
        $conf = parse_ini_file(__DIR__ . '/../conf/urlmask.ini', true);
        if(isset($conf[$env]['salt'], $conf[$env]['tt_host'], $conf[$env]['tt_port'], $conf[$env]['front_url']) === true)
        {
            self::$salt = $conf[$env]['salt'];
            self::$tt_host = $conf[$env]['tt_host'];
            self::$tt_port = $conf[$env]['tt_port'];
            self::$front_url = $conf[$env]['front_url'];
        }
    }

    public function execute()
    {
        $render = $this->getRenderData();
        self::renderJson($render);
    }

    private function getRenderData()
    {
        if(empty($_GET['url']) === true)
        {
            return array('status' => 400, 'message' => 'Invalid parameter.');
        }

        $raw_url = htmlentities($_GET['url'], ENT_QUOTES, 'UTF-8', true);
        if(strlen($raw_url) > self::MAX_URL_LENGTH)
        {
            return array('status' => 414, 'message' => 'URL length must be under ' . self::MAX_URL_LENGTH . ' bytes.');
        }

        $hash_url = $this->getHashUrl($raw_url);
        if(is_null($hash_url) === true)
        {
            return array('status' => 500, 'message' => 'Register url failed.');
        }

        return array('status'=> 200, 'raw_url' => $raw_url, 'hash_url' => self::$front_url . '/' . $hash_url);
    }

    private function getHashUrl($raw_url)
    {
        if(empty(self::$salt) == false && empty(self::$tt_host) === false && empty(self::$tt_port) === false)
        {
            $hash_url = ShortHashConverter::convertStringToHash($raw_url, self::$salt);
            $tt = new TokyoTyrantConnector(self::$tt_host, self::$tt_port);
            if(is_null($tt->getValue($hash_url)) === true)
            {
                if($tt->setValue($hash_url, $raw_url) === true)
                {
                    return $hash_url;
                }
                else
                {
                    return null;
                }
            }
            else
            {
                return $hash_url;
            }
        }
        else
        {
            return null;
        }
    }

    private static function renderJson(array $render)
    {
        header('HTTP/1.1 ' . $render['status']);
        echo json_encode($render);
    }
}

