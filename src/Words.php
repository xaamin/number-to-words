<?php
namespace Xaamin\NumberToWords;

class Words
{
    private $unidades = [
        '',
        'Un ',
        'Dos ',
        'Tres ',
        'Cuatro ',
        'Cinco ',
        'Seis ',
        'Siete ',
        'Ocho ',
        'Nueve ',
        'Diez ',
        'Once ',
        'Doce ',
        'Trece ',
        'Catorce ',
        'Quince ',
        'Dieciseis ',
        'Diecisiete ',
        'Dieciocho ',
        'Diecinueve ',
        'Veinte '
    ];

    private $decenas = [
        'Veinti',
        'Treinta ',
        'Cuarenta ',
        'Cincuenta ',
        'Sesenta ',
        'Setenta ',
        'Ochenta ',
        'Noventa ',
        'Cien '
    ];

    private $centenas = [
        'Ciento ',
        'Doscientos ',
        'Trescientos ',
        'Cuatrocientos ',
        'Quinientos ',
        'Seiscientos ',
        'Setecientos ',
        'Ochocientos ',
        'Novecientos '
    ];

    protected $singular = '';
    protected $plural = '';
    protected $decimal = '';

    /**
     * @var \Xaamin\NumberToWords\Currencies\Currency
     */
    protected $divisa;

    protected $moneda = 'MXN';

    protected $currency;
    private $separator = ',';
    private $decimal_mark = '.';
    private $glue = ' con ';

    /**
     * Constructor
     *
     * @param \Xaamin\NumberToWords\CurrencyManager $currency
     */
    public function __construct(CurrencyManager $currency = null)
    {
        $this->currency = $currency ? : new CurrencyManager;

        $this->using($this->moneda);
    }

    /**
     * Converts an integer or decimal number to text
     *
     * @param $number The given number
     * @param $currency Currency key
     * @return string Text for the given number
     */
    public function from($number, $currency = null)
    {
        if ($currency) {
            $this->using($currency);
        };

        $converted = [];

        if (strpos($number, $this->decimal_mark) === false) {
            $converted = [
                $this->convert($number, 'entero')
            ];
        } else {
            $number = str_replace($this->separator, '', $number);
            $number = explode($this->decimal_mark, trim($number));

            $converted = [
                $this->convert($number[0], 'entero'),
                $this->convert($number[1], 'decimal'),
            ];
        }

        $entero = array_shift($converted);
        $decimal = array_shift($converted);

        $converted = $entero . $this->glue;

        $converted .= $decimal ? : '00/100 ';

        if (is_callable('mb_strtolower')) {
            $converted = ucfirst(mb_strtolower($converted, 'UTF-8'));
        } else {
            $converted = ucfirst(strtolower($converted));
        }

        $converted .= $this->divisa;

        return preg_replace('/\s+/', ' ', $converted);
    }

    public function using($moneda)
    {
        $currency = $this->currency->get($moneda);

        $this->singular = $currency['singular'];
        $this->plural = $currency['plural'];
        $this->decimal = '/100 ' . $currency['sufix'];
        $this->divisa = $currency['sufix'];

        return $this;
    }

    /**
     * Convert the giving digits to text representation
     *
     * @param $number
     * @param $type Digits type (entero/decimal)
     * @return string Cifra en letras
     */
    private function convert($number, $type)
    {
        $converted = '';

        $moneda = ($number < 2 && $number > 0) ? $this->singular : $this->plural;
        $greaterThanMil = $number > 999;

        if (($number < 0) || ($number > 999999999999)) {
            return false;
        }

        $numberStr = $type == 'decimal' ? substr($number, 0, 3) : $number;

        if ($type == 'decimal') {
            return substr($numberStr, 0, 2) . $this->decimal;
        }

        $numberStr = str_pad($numberStr, 3, '0', STR_PAD_LEFT);

        $pad = 3 - (strlen($numberStr) % 3) + strlen($numberStr);

        $numberStrFill = str_pad($numberStr, $pad, '0', STR_PAD_LEFT);

        $exploded = str_split($numberStrFill, 3);

        $cientos = array_pop($exploded);
        $miles = array_pop($exploded);
        $millones = array_pop($exploded);
        $billones = array_pop($exploded);

        if (intval($billones) > 0) {
            if ($billones == '001') {
                $converted .= 'UN BILLON ';
            } elseif (intval($billones) > 0) {
                $converted .= sprintf('%sBILLONES ', $this->group($billones));
            }

            if (!intval($millones) && !intval($miles) && !intval($cientos)) {
                $converted .= 'DE ';
            }
        }

        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } elseif (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', $this->group($millones));
            }

            if (!intval($miles) && !intval($cientos)) {
                $converted .= 'DE ';
            }
        }

        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } elseif (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', $this->group($miles));
            }
        }

        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $converted .= 'UN ';
            } elseif (intval($cientos) > 0) {
                $converted .= sprintf('%s ', $this->group($cientos));
            }
        } else if (!$greaterThanMil) {
            $converted .= 'CERO ';
        }

        $converted = $converted . $moneda;

        return trim($converted);
    }

    /**
     * Defines the decimal representation needed (centenas/millares/millones)
     *
     * @param $n
     * @return $output
     */
    private function group($n)
    {
        $output = '';

        if ($n == '100') {
            $output = "CIEN ";
        } elseif ($n[0] !== '0') {
            $output = $this->centenas[$n[0] - 1];
        }

        $k = intval(substr($n,1));

        if ($k <= 20) {
            $output .= $this->unidades[$k];
        } elseif ($k > 30 && $n[2] !== '0') {
            $output .= sprintf('%sY %s', $this->decenas[intval($n[1]) - 2], $this->unidades[intval($n[2])]);
        } else {
            $output .= sprintf('%s%s', $this->decenas[intval($n[1]) - 2], $this->unidades[intval($n[2])]);
        }

        return $output;
    }
}