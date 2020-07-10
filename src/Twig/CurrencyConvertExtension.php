<?php


namespace App\Twig;


use App\Service\ConvertCurrencyService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CurrencyConvertExtension extends AbstractExtension
{
    private $converter;

    public function __construct(ConvertCurrencyService $converter)
    {
        $this->converter = $converter;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('currencyConverter', [$this , 'displayConverter']) ,
        ];
    }

    public function displayConverter($amount , $fromCurrency , $toCurrency){

        return $this->converter->convert($amount , $fromCurrency , $toCurrency);
    }

}