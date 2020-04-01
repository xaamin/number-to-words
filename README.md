# Number to words
Currency based number to words converted

## Usage

```php
use Xaamin\NumberToWords\Words;

$converter = new Words();
$words = $converter->from('100.01', 'MXN');

// USD
$words = $converter->from('100.01', 'USD');

// EUR
$words = $converter->from('100.01', 'EUR');
```

### Register new currencies

```php
use Xaamin\NumberToWords\Words;
use Xaamin\NumberToWords\CurrencyManager;
use Xaamin\NumberToWords\Currencies\Currency;

class CanadianDolar extends Currency
{
    public function getName()
    {
        return 'CAD';
    }

    public function getMeta()
    {
        return [
            'singular' => 'DÓLAR',
            'plural' => 'DÓLARES',
            'prefix' => 'CAD',
            'sufix' => 'CAD',
            'symbol' => 'CAD.',

        ];
    }
}

$manager = new CurrencyManager();
$manager->register(new CanadianDolar());

$converter = new Words($manager);
$words = $converter->from('100.01');
```