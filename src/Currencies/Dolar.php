<?php
namespace SatSuite\NumberToWords\Currencies;

use SatSuite\NumberToWords\Currencies\Currency;

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