<?php // -*- encoding utf-8 -*-

require_once(__DIR__ . '/../../models/HashUrl.php');

class HashUrlTest extends PHPUnit_Framework_TestCase
{

    private $hash_url = null;

    public function setUp()
    {
        if($this->hash_url === null)
        {
            $this->hash_url = new HashUrl('localhost');
        }
        $this->hash_url->getInstance()->vanish();
        $this->hash_url->getInstance()->putKeep('test_key', 'test_value');
    }

    public function tearDown()
    {
        $this->hash_url->getInstance()->vanish();
    }

    /**
    * @dataProvider getValueProvider
    */
    public function testGetValue($key, $expected_value)
    {
        $this->assertSame($expected_value, $this->hash_url->getValue($key));
    }


    public function getValueProvider()
    {
        $provider = array();
        $provider[] = array('test_key', 'test_value');
        $provider[] = array('empty', null);
        return $provider;
    }

    public function testGetRecordNum()
    {
        $this->assertSame(1, $this->hash_url->getRecordNum());
    }

    /**
    * @dataProvider setValueProvider
    */
    public function testSetValue($key, $value, $expected)
    {
        $this->assertSame($expected, $this->hash_url->setValue($key, $value));
    }

    public function setValueProvider()
    {
        $provider = array();
        $provider[] = array('test_insert', 'hoge', true);
        $provider[] = array('test_key', 'hoge', false);
        return $provider;
    }

    /**
    * @dataProvider deleteValueProvider
    */
    public function testDeleteValue($key, $expected)
    {
        $this->assertSame($expected, $this->hash_url->deleteValue($key));
    }

    public function deleteValueProvider()
    {
        $provider = array();
        $provider[] = array('test_key', true);
        $provider[] = array('test_delete', false);
        return $provider;
    }

    public function testDeleteAllValues()
    {
        $this->assertTrue($this->hash_url->deleteAllValues());
    }

    /**
    * @dataProvider isKeyProvider
    */
    public function testIsKey($key, $expected)
    {
        $this->assertSame($expected, HashUrlFacade::testIsKey($key));
    }

    public function isKeyProvider()
    {
        $provider = array();
        $provider[] = array('key', true);
        $provider[] = array(1, true);
        $provider[] = array('', false);
        $provider[] = array(null, false);
        $provider[] = array(true, false);
        $provider[] = array(false, false);
        $provider[] = array(array(), false);
        return $provider;
    }

    /**
    * @dataProvider isValueProvider
    */
    public function testIsValue($value, $expected)
    {
        $this->assertSame($expected, HashUrlFacade::testIsValue($value));
    }

    public function isValueProvider()
    {
        $provider = array();
        $provider[] = array('key', true);
        $provider[] = array(1, true);
        $provider[] = array('', true);
        $provider[] = array(null, false);
        $provider[] = array(true, false);
        $provider[] = array(false, false);
        $provider[] = array(array(), false);
        return $provider;
    }
}

class HashUrlFacade extends HashUrl
{
    public static function testIsKey($key)
    {
        return parent::isKey($key);
    }

    public static function testIsValue($value)
    {
        return parent::isValue($value);
    }
}
