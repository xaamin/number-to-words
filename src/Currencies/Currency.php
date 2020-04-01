<?php
namespace SatSuite\NumberToWords\Currencies;

use ArrayAccess;
use SatSuite\NumberToWords\Contracts\CurrencyInterface;

abstract class Currency implements CurrencyInterface, ArrayAccess
{
    abstract public function getName();

    abstract public function getMeta();

    public function is($currency)
    {
        return $this->getName() === $currency;
    }

    public function offsetExists($offset)
    {
        return isset($this->getMeta()[$offset]);
    }

    public function offsetGet($offset)
    {
        $data = $this->getMeta();

        return isset($data[$offset]) ? $data[$offset] : null;
    }

    public function offsetSet($offset , $value)
    {

    }

    public function offsetUnset($offset)
    {

    }

}