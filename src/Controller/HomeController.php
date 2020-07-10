<?php


namespace App\Controller;


use App\Service\MyLogger;
use App\Service\Notificator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
            'http://monblog/test-socket/5',
            json_encode([
                'message' => 'toto'
            ])
        );


        $publisher($update);

//
//        $notificator->privateNotification('http://monblog/test-socket/5', [
//            'message' => 'toto'
//        ]);

        return new Response('published!');
    }

    public function getChatAjax(Request $request ,Notificator $notificator){
        $message = $request->request->get('message');
        $id = $request->request->get('id');

        $notificator->publicNotification('http://monblog/test-socket/'.$id, [
            'message' => $message
        ]);

        return $this->json(1);
    }
}

