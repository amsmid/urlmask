<?php // -*- encoding utf-8 -*-

class TokyoTyrantConnector 
{

    const TIMEOUT_SEC = 5.0;
    const RECONNECT_FLAG = true;
    const PERSISTENT_FLAG = true;

    private $tt = null;
    private $trace = null;

    public function __construct($host = 'localhost',
        $port = TokyoTyrant::RDBDEF_PORT,
        $timeout = self::TIMEOUT_SEC,
        $reconnect = self::RECONNECT_FLAG,
        $persistent = self::PERSISTENT_FLAG)
    {
        $this->tt = null;
        $this->trace = null;
        $option = array();
        try
        {
            $option['timeout'] = $timeout;
            $option['reconnect'] = $reconnect;
            $option['persistent'] = $persistent;
            $this->tt = new TokyoTyrant($host, $port, $option);
        }
        catch(TokyoTyrantException $e)
        {
            $this->trace = $e->getTraceAsString();
        }
    }

    public function getValue($key)
    {
        if($this->tt !== null && self::isKey($key) === true)
        {
            return $this->tt->get($key);
        }
        else
        {
            return null;
        }
    }

    public function getRecordNum()
    {
        if($this->tt !== null)
        {
            return $this->tt->num();
        }
        else
        {
            return null;
        }
    }

    public function setValue($key, $value)
    {
        if($this->tt !== null && self::isKey($key) === true &&
            self::isValue($value) === true && is_null($this->getValue($key)) === true)
        {
            $this->tt->putKeep($key, $value);
            return !is_null($this->getValue($key));
        }
        else
        {
            return false;
        }
    }

    public function deleteValue($key)
    {
        if($this->tt !== null && self::isKey($key) === true && is_null($this->getValue($key)) === false)
        {
            $this->tt->out($key);
            return is_null($this->getValue($key));
        }
        else
        {
            return false;
        }
    }

    public function deleteAllValues($dry_run = false)
    {
        if($this->tt !== null)
        {
            if($dry_run === false)
            {
                $this->tt->vanish();
                return ($this->getRecordNum() === 0);
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    public function getInstance()
    {
        return $this->tt;
    }

    public function getTraceAsString()
    {
        return $this->trace;
    }

    protected static function isKey($key)
    {
        return (is_null($key) === false && is_bool($key) === false && is_array($key) === false && $key !== '');
    }

    protected static function isValue($value)
    {
        return (is_null($value) === false && is_bool($value) === false && is_array($value) === false);
    }
}
