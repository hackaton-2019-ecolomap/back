<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Category;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class CategorySubscriber implements EventSubscriberInterface
{

//    private $category;
//
//    public function __construct(Category $category)
//    {
//        $this->category = $category;
//    }


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['getReferencedArticles', EventPriorities::PRE_SERIALIZE],
        ];
    }


    public function getReferencedArticles(GetResponseForControllerResultEvent $event)
    {

        $url = "https://my-json-server.typicode.com/typicode/demo/posts";



        try {
            $ch = curl_init();

            // Check if initialization had gone wrong*
            if ($ch === false) {
                $event->setControllerResult('failed to initialize');
            }

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $content = curl_exec($ch);

            // Check the return value of curl_exec(), too
            if ($content === false) {
                $event->setControllerResult(curl_error($ch));
            }

            /* Process $content here */

            // Close curl handle
            curl_close($ch);
        } catch(Exception $e) {
            $event->setControllerResult('Curl failed with error');


        }






//        $ch = curl_init($url);

//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        $content  = curl_exec($ch);

//        curl_close($ch);


//        $event->setControllerResult($content);

    }
}