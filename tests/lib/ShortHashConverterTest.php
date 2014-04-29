<?php // -*- encoding utf-8 -*-

require_once(__DIR__ . '/../../lib/ShortHashConverter.php');

class ShortHashConverterTest extends PHPUnit_Framework_TestCase
{
    private static $salt = 'hoge';

    public function testConvertStringToHash()
    {
        $this->assertSame('OHAJZVM', ShortHashConverter::convertStringToHash('http://www.nicovideo.jp/', self::$salt));
    }

    public function testConvertStringToHash_emptyStr()
    {
        $this->assertNull(ShortHashConverter::convertStringToHash('', self::$salt));
    }
}
