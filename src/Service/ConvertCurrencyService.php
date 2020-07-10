<?php


namespace App\Service;

use App\Entity\CurrencyRate;
use App\Repository\CurrencyRateRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Exception;

class ConvertCurrencyService
{
    private $em;
    private $rate;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $fromCurrency
     * @param $toCurrency
     * @return object
     */
    public function getRate($fromCurrency , $toCurrency){
        if($this->rate !== null){
            return $this->rate ;
        }

        if($fromCurrency == $toCurrency){
           $this->rate = 1;
           return $this->rate;
        }

        $rateRecord = $this->em->getRepository(CurrencyRate::class)->findOneBy(
            [
                "fromCurrency" => $fromCurrency,
                'toCurrency' => $toCurrency,
            ],
            [
                'validityDate' => 'desc'
            ]
        );

        if(!$rateRecord){
            throw new \Exception('IDK this currency');
        }

        $this->rate = $rateRecord->getRate();

        return $this->rate ;
    }

    public function checkRate($rate , $fromCurrency, $toCurrency){

        $currentRate = $this->getRate($fromCurrency , $toCurrency);

        if($rate === $currentRate)
        {
            return true ;
        }

        //FIXME: CheckPreviousRate pour checker une fois
        /** @var CurrencyRateRepository $repo */
        $repo = $this->em->getRepository(CurrencyRate::class);
        try{
            $previousRate = $repo->getPreviousRate();
        }
        catch(\Exception $e){
            return false;
        }

        if($rate === $previousRate){
            return true;
        }

        return false;



    }

    public function convert($amount , $fromCurrency , $toCurrency)
    {
        return $amount * $this->getRate($fromCurrency , $toCurrency);
    }

    public function storeCurrencyPairRates(string $fromCurrency , string $toCurrency, $rate, \DateTime $date){

        $currencyRate = new CurrencyRate();
        $currencyRate->setFromCurrency($fromCurrency);
        $currencyRate->setToCurrency($toCurrency);
        $currencyRate->setRate($rate);
        $currencyRate->setValidityDate($date);
        $this->em->persist($currencyRate);
        $this->em->flush();
    }

}