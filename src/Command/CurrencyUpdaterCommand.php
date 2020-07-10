<?php


namespace App\Command;


use App\Service\ConvertCurrencyService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;

class CurrencyUpdaterCommand extends Command
{
    private $apiKey;
    private $baseUrl;
    private $currencyConverter;

    protected static $defaultName = "currency:grab";

    public function __construct($apiKey , $baseUrl, ConvertCurrencyService $currencyConverter)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
        $this->currencyConverter = $currencyConverter;

        parent::__construct();

    }

    protected function configure(){

        $this
            ->setDescription('Grab the last currency rate and store in db');
    }

    public function execute(InputInterface $input , OutputInterface $output)
    {
        $output->writeln("start grabing currency rates...") ;
        $start = microtime(true) ;
        $endpoint = $this->baseUrl.'live';
        $client = HttpClient::create();
        $response = $client->request('GET', $endpoint ,[
            'query' => [
                'access_key' => $this->apiKey,
                'currencies' => 'EUR',
            ],
        ]);

        $apiResponse = $response->toArray();
        $output->writeln(print_r($apiResponse));
        $now = new \DateTime('now');

        $rate = $apiResponse['quotes']['USDEUR'];
        $this->currencyConverter->storeCurrencyPairRates('USD', 'EUR', $rate, $now);

        $rate = 1 / $rate;
        $this->currencyConverter->storeCurrencyPairRates('EUR', 'USD', $rate, $now);

        $elapsed = microtime(true) - $start ;
        $output->writeln("currency rates fetched and stored in $elapsed sec.") ;


        return Command::SUCCESS;
    }





}