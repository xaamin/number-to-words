<?php
namespace Xaamin\NumberToWords\Currencies;

use Xaamin\NumberToWords\Currencies\Currency;

class PesoMexicano extends Currency
{
    public function getName()
    {
        return 'MXN';
    }

    public function getMeta()
    {
        return [
            'singular' => 'PESO',
            'plural' => 'PESOS',
            'prefix' => 'MXN',
            'sufix' => 'M.N.',
            'symbol' => '$',
        ];
    }
}