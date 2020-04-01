<?php
namespace SatSuite\NumberToWords;

use SatSuite\NumberToWords\Currencies\Euro;
use SatSuite\NumberToWords\Currencies\Dolar;
use SatSuite\NumberToWords\Currencies\PesoMexicano;
use SatSuite\NumberToWords\Contracts\CurrencyInterface;
use SatSuite\NumberToWords\Exceptions\UnsupportedCurrencyException;

class CurrencyManager
{
    protected $currencies = [];

    public function __construct()
    {
        $this->boot();
    }

    protected function boot()
    {
        $this->register(new Dolar());
        $this->register(new Euro());
        $this->register(new PesoMexicano());
    }

    public function register(CurrencyInterface $currency)
    {
        $exists = false;

        foreach ($this->currencies as $index => $current) {
            if ($current->getName() === $currency->getName()) {
                $this->currencies[$index] = $current;

                $exists = true;
            }
        }

        if (!$exists) {
            $this->currencies[] = $currency;
        }

        return $this;
    }

    public function get($currency)
    {
        foreach ($this->currencies as $instance) {
            if ($instance->is($currency)) {
                return $instance;
            }
        }

        throw new UnsupportedCurrencyException("Currency {$currency} is not supported");
    }

    public function all()
    {
        return $this->currencies;
    }
}