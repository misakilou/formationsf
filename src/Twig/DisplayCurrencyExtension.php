<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DisplayCurrencyExtension extends AbstractExtension
{

    public function getFilters()
    {
        return [
            new TwigFilter('displayCurrencyFormatter', [$this , 'displayCurrency']) ,
        ];
    }

    public function displayCurrency($amount, $currencySymbol = null)
    {
        if($currencySymbol){
            return number_format($amount, 2 , "&nbsp$currencySymbol&nbsp", ' ');
        }

       return number_format($amount, 2 , '.', ' ');
    }

}