<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Пользователь
 * Date: 15.01.2018
 * Time: 12:26
 */


class StringLang
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

    }


//    Основная функция, в которой будет инициализация, работа второстепенных функций с условием языка
//    и возврат строки
    public function returnString($number, $currencyLang, $stringLang) :string
    {
        $this->setValues($number, $currencyLang, $stringLang);
        $this->separationNumber();
        $stringNumber = $this->getStringMain();
        return $stringNumber;
    }

    private function getStringMain() {
        $this->getStatusCurrency();
        if ($this->checkRightNumbers())  {
            $query = $this->db->query(
                'SELECT string FROM '.$this->stringLang.' WHERE number='.$this->amount.
                ' AND (indicate = 1 or indicate = '.$this->currencyStatus.')'
            );
            $stringNumber = $query->result;
        } else {
            $stringNumber = '';
        }
        return $stringNumber;
    }

    protected function checkRightNumbers()
    {
        if ($this->db->simple_query(
            'SELECT * FROM '.$this->stringLang.' WHERE number = '.$this->amount
        )) {
            return true;
        } else {
            return false;
        }
    }

    private function getStatusCurrency()
    {
        if (in_array($this->currencyLang, $this->arrayCurrencyStatus[0])) {
            $this->currencyStatus = 2;
        } else {
            $this->currencyStatus = 3;
        }
    }

    private function separationNumber() {
        $arr = explode('.', $this->number);
        $this->amount = $arr[0];
        $this->cents = $arr[1];
    }

    private function setValues($number, $currencyLang, $stringLang)
    {
        $this->currencyLang = $currencyLang;
        $this->stringLang = $stringLang;
        $this->number = str_replace(' ', '', $number);
    }
}