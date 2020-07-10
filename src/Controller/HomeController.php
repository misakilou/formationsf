<?php


namespace App\Controller;


use App\Service\MyLogger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $logger;

    public function __construct(MyLogger $logger)
    {
        $this->logger = $logger;
    }


    public function main(){

        /*$this->logger->write('toto');*/

        return $this->render('home/main.html.twig',
            [
                "message" => "Coucou",
            ]
        );
    }

//
//    public function indexByPage($page){
//
//        die('Affiche la page '.$page);
//
//    }
//
//    public function indexByLetter($letter){
//        die('Affiche la page '.$letter);
//    }

    public function TestMercure(PublisherInterface $publisher)
    {
        $update = new Update(
            'http://monblog/test-socket',
            json_encode(
                [
                    'status' => 'OutOfStock '.date_create('now')->format('d/m/Y H:i:s'),
                ]
            )
        );

        // Sync, or async (RabbitMQ, Kafka...)
        $publisher($update);

        return new Response('published!');
    }

}

