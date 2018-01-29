<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Пользователь
 * Date: 15.01.2018
 * Time: 12:26
 */


class StringLang extends CI_Model
{
    //валюта
    protected $currencyLang;

    //язык на котором вернется строка
    protected $stringLang;

    //сумма еще не разделенная
    protected $number;

    //сумма без копеек
    protected $amount;

    //копейки
    protected $cents;

    protected $currencyStatus;

    private $arrayCurrencyStatus = [
        [
            'rur', 'eur', 'pln', 'gel', 'usd', 'gbp',
        ],
        [
            'uah',
        ]
    ];

    public function __construct()
    {
        parent::__construct();
    }


//    Основная функция, в которой будет инициализация, работа второстепенных функций с условием языка
//    и возврат строки
    public function returnString($number, $currencyLang, $stringLang)
    {
        $this->setValues($number, $currencyLang, $stringLang);
        $this->separationNumber();
        $stringNumber = $this->getStringMain();
        return $stringNumber;
    }

    private function getStringMain()
    {
        return $this->getStringCurrency().' '.$this->getCentString();
    }


    //возврачает строку с валютой
    private function getStringCurrency()
    {
        $this->getStatusCurrency();
        $currency = $this->getCurrencyStringSecondPart();
        if ($this->checkRightNumbers($this->amount)) {
            $number = $this->checkRightNumbers($this->amount);
        } else {
            $number = $this->getStringAmount($this->amount);
        }

        return $number.' '.$currency;
    }


    private function getStringAmount($amount, $value = null)
    {
        if ($value == 1000) {
            $this->currencyStatus = 3;
            if ($this->stringLang == 'pl') {
                $this->currencyStatus = 2;
            }
        } elseif ($value == 1000000){
            $this->currencyStatus = 2;
        } else {
            $this->getStatusCurrency();
        }
        if ($this->stringLang == 'es') {
            $this->currencyStatus = 4;
        }
        $length = strlen($amount);
        switch ($length) {
            case 2:
                if ($this->checkRightNumbers($amount)) {
                    return $this->checkRightNumbers($amount);
                } else {
                    return $this->getStringAmountDigit2($amount);
                }
                break;
            case 3:
                if ($this->checkRightNumbers($amount)) {
                    return $this->checkRightNumbers($amount);
                } else {
                    return $this->getStringAmountDigit3($amount);
                }
                break;
            case 4:
            case 5:
            case 6:
                return $this->getStringAmountDigitBigNumber($amount);
            case 7:
            case 8:
            case 9:
                return $this->getStringAmountDigitVeryBigNumber($amount);
            default:
                return "Очень длинное число";
        }
    }

    //строка мелочи для всех валют, уже свормированна
    private function getCentString()
    {
        if ($this->stringLang == 'ru' || $this->stringLang == 'ua' ||  $this->stringLang == 'pl') {
            $status = $this->getUkrAndRusStringValue($this->cents);
        } elseif ($this->stringLang == 'en' || $this->stringLang == 'es') {
            $status = $this->getEngStringValue($this->cents);
        } else {
            $status = 1;
        }
        $query = $this->db->query(
            'SELECT word FROM cent'.$this->stringLang.' WHERE 
            currency="'.$this->currencyLang.'" AND value='.$status
        );
        $response = $query->result();
        return $this->cents.' '.$response[0]->word;
    }

    //возвращает валюту
    private function getCurrencyStringSecondPart ()
    {
        if ($this->stringLang == 'ru' || $this->stringLang == 'ua' ||  $this->stringLang == 'pl') {
            $status = $this->getUkrAndRusStringValue($this->amount);
        } elseif ($this->stringLang == 'en' || $this->stringLang == 'es') {
            $status = $this->getEngStringValue($this->amount);
        } else {
            $status = 1;
        }
        $query = $this->db->query(
            'SELECT word FROM currency'.$this->stringLang.' WHERE
            currency="'.$this->currencyLang.'" AND value='.$status
        );
        $response = $query->result();
        return $response[0]->word;
    }

    //проверяет правильное ли это число, если есть в бд то сразу выводится
    protected function checkRightNumbers($amount)
    {
        $query = $this->db->query(
            'SELECT * FROM '.$this->stringLang.' WHERE number = '.$amount
        );
        if (!empty($query->result())) {
            $query = $this->db->query(
                'SELECT string FROM '.$this->stringLang.' WHERE (number='.$amount.
                ') AND (indicate = 1 or indicate = '.$this->currencyStatus.')'
            );
            $stringNumber = $query->result();
            return $stringNumber[0]->string;
        } else {
            return false;
        }
    }


    //разделяет входно число по точке на копейки и нет
    private function separationNumber() {
        $arr = explode('.', $this->number);
        $this->amount = $arr[0];
        if (!empty($arr[1])){
            $this->cents = $arr[1][0].$arr[1][1];
        } else {
            $this->cents = '00';
        }

    }

    private function setValues($number, $currencyLang, $stringLang)
    {
        $this->currencyLang = $currencyLang;
        $this->stringLang = $stringLang;
        $number = str_replace(',', '.', $number);
        $this->number = str_replace(' ', '', $number);
    }



    private function getStringAmountDigit2($amount)
    {
        $tens = $amount[0] * 10;
        $firstPart = $this->checkRightNumbers($tens);
        $secondPart = $this->checkRightNumbers($amount[1]);
        if ($this->stringLang == 'es') {
            $firstPart = $firstPart.' y ';
        }
        return $firstPart.' '.$secondPart;
    }

    private function getStringAmountDigit3($amount)
    {
        $hundred = $amount[0] * 100;
        $tens = $amount[1].$amount[2];
        if ($hundred == 0) {
            $firstPart = '';
        } else {
            if ($this->checkRightNumbers($hundred)) {
                if ($this->stringLang == 'es') {
                    $this->currencyStatus = 4;
                }
                $firstPart = $this->checkRightNumbers($hundred);
            } else {
                $firstPart = "error getStringAmountDigit3";
            }
        }
        if ($tens == 0) {
            $secondPart = '';
        } else {
            if ($this->checkRightNumbers($tens)) {
                $secondPart = $this->checkRightNumbers($tens);
            } else {
                $secondPart = $this->getStringAmountDigit2($tens);
            }
        }

        return $firstPart." ".$secondPart;

    }

    private function getStringAmountDigitBigNumber($amount)
    {
        $secondPart = substr($amount, -3, 3);
        $firstPart = substr($amount, 0, strlen($amount) - 3);
        $secondPart = $this->getStringAmountDigit3($secondPart);
        $firstPart = $this->getThousand($firstPart, 1000);

        return $firstPart.' '.$secondPart;
    }

    private function getThousand($amount, $value)
    {
        if ($amount == 0) {
            return '';
        }
        if ($this->checkRightNumbers($amount)) {
            if ($value == 1000) {
                $this->currencyStatus = 3;
                if ($this->stringLang == 'pl') {
                    $this->currencyStatus = 2;
                }
            } elseif ($value == 1000000){
                $this->currencyStatus = 2;
            } else {
                $this->getStatusCurrency();
            }
            if ($this->stringLang == 'es') {
                $this->currencyStatus = 4;
            }
            $firstPart = $this->checkRightNumbers($amount);
        } else {
            $firstPart = $this->getStringAmount($amount, $value);
        }
        switch ($this->stringLang) {
            case 'ru':
            case 'ua':
            case 'en':
            case 'gr':
            case 'pl':
            case 'es':
                $secondPart = $this->getThousandAndMillion($amount, $value);
                break;
            default:
                $secondPart = 'error getThousand';
        }
        return $firstPart.' '.$secondPart;
    }

    private function getStringAmountDigitVeryBigNumber($amount)
    {

        $secondPart = substr($amount, -6, 6);
        $firstPart = substr($amount, 0, strlen($amount) - 6);
        $secondPart = $this->getStringAmountDigitBigNumber($secondPart);
        $firstPart = $this->getThousand($firstPart, 1000000);

        return $firstPart.' '.$secondPart;
    }

    private function getThousandAndMillion($number, $value)
    {
        if ($this->stringLang == 'ru' || $this->stringLang == 'ua' || $this->stringLang == 'pl') {
            $indicate = $this->getUkrAndRusStringValue($number);
        } elseif ($this->stringLang == 'en' || $this->stringLang == 'es') {
            $indicate = $this->getEngStringValue($number);
        } else {
            $indicate = 1;
        }
        $query = $this->db->query(
            'SELECT string FROM '.$this->stringLang.' WHERE number='.$value.' AND indicate='.$indicate
        );
        $result = $query->result();
        return $result[0]->string;
    }

    //украина и россия

    //возвращает статус мелочи
    private function getUkrAndRusStringValue($variable)
    {
        $last = substr($variable, -1);
        $last2 = substr($variable, -2);
        if ($last2 > 10 && $last2 < 16 ) {
            return 3;
        } elseif ($last == 1) {
            return 1;
        } elseif ($last == 2 || $last == 3 || $last == 4 ) {
            return 2;
        } else {
            return 3;
        }
    }

    //определяет для рос. и Украины род (один/одна)
    private function getStatusCurrency()
    {
        if (in_array($this->currencyLang, $this->arrayCurrencyStatus[0])) {
            $this->currencyStatus = 2;
        } else {
            $this->currencyStatus = 3;
        }
    }


    //england
    private function getEngStringValue($variable)
    {
        $last = substr($variable, -1);
        if ($last == 1) {
            return 1;
        } else {
            return 2;
        }
    }




}