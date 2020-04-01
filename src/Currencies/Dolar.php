<?php
namespace Xaamin\NumberToWords\Currencies;

use Xaamin\NumberToWords\Currencies\Currency;

class Dolar extends Currency
{
    public function getName()
    {
        return 'USD';
    }

    public function getMeta()
    {
        return [
            'singular' => 'DÓLAR',
            'plural' => 'DÓLARES',
            'prefix' => 'USD',
            'sufix' => 'USD',
            'symbol' => '$',

        ];
    }
}