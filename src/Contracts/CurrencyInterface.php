<?php
namespace Xaamin\NumberToWords\Contracts;

interface CurrencyInterface
{
    public function getName();

    public function getMeta();

    public function is($currency);
}
