<?php


namespace App\Service;


use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

class Notificator
{
    private $publisher;

    public function __construct(PublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }

    public function publicNotification(string $topics , array $params){
        $update = new Update(
            $topics,
            json_encode($params)
        );

        $publisher = $this->publisher;
        $publisher($update);

    }

    public function privateNotification(string $topics ,  array $params){
        $update = new Update(
            $topics,
            json_encode($params),
            true
        );

        $publisher = $this->publisher;
        $publisher($update);

    }

}