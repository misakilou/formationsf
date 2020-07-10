<?php


namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Validator\Constraints\Date;

class LogginSubscriber implements EventSubscriberInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onLogin(InteractiveLoginEvent $event){
        /** @var User $user */
        $user = $event->getAuthenticationToken()->getUser();

        $user->setLastConnectionDate(new \DateTime('now'));
        $this->em->persist($user);
        $this->em->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
          SecurityEvents::INTERACTIVE_LOGIN =>
            ['onLogin', 0],
        ];
    }

}