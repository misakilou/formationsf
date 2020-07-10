<?php


namespace App\EventListener;


use App\Entity\Article;
use App\Service\ExplicitContentCheckerService;
use App\Service\MyLogger;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CreateArticleExplicitContentListener
{
    private $logger;
    private $explicitContentChecker;

    public function __construct(MyLogger $logger, ExplicitContentCheckerService $explicitContentChecker)
    {
        $this->logger = $logger;
        $this->explicitContentChecker = $explicitContentChecker;
    }

    public function postPersist(Article $article , LifecycleEventArgs $event){

        $content = $article->getContent();
        if($this->explicitContentChecker->checkContent($content))
        {
            $this->logger->write('Warning !! dans '.$article->getTitle());
        }
    }
}