<?php


namespace App\EventSubscriber;


use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AJAXBeforeFIlterSubscriber
{
    public function onController(ControllerEvent $event){
       $controller = $event->getController();

       if(is_array($controller)){
           $method_name = $controller[1];
            if(
                strpos($method_name,'Ajax') !== false
                && $event->getRequest()->isXmlHttpRequest()
            )
            {
                throw new AccessDeniedException('Ajax only');
            }
       }
    }


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER =>
                ['onController', 0],
        ];
    }

}