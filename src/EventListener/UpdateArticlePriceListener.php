<?php


namespace App\EventListener;


use App\Entity\Article;
use App\Service\Notificator;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

class UpdateArticlePriceListener
{
    private $notificator;
    public function __construct(Notificator $notificator)
    {
        $this->notificator = $notificator;
    }

    public function preUpdate(Article $article , PreUpdateEventArgs $args){
        if(!$args->hasChangedField('amount'))
        {
            return;
        }

        $this->notificator->publicNotification('http://monblog/article-price' , ['id' => $article->getId() , 'newPrice' => $article->getAmount()]);


//        $update = new Update(
//            'http://monblog/article-price',
//            json_encode(
//                [
//                    'id' => $article->getId(),
//                    'newPrice' => $article->getAmount(),
//                ]
//            )
//        );
//
//        // Sync, or async (RabbitMQ, Kafka...)
//        $publisher = $this->publisher  ;
//        $publisher($update);

    }

}