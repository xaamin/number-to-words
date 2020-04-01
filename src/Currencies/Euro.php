<?php
namespace SatSuite\NumberToWords\Currencies;

use SatSuite\NumberToWords\Currencies\Currency;

class Euro extends Currency
{
    public function getName()
    {
        return 'EUR';
    }

    public function getMeta()
    {
        return [
            'singular' => 'EURO',
            'plural' => 'EUROS',
            'prefix' => 'EUR.',
            'sufix' => 'EUR',
            'symbol' => '€',
        ];
    }
}