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

        $url_pollution = "https://api.ozae.com/gnw/articles?date=20180305__20180312&key=11116dbf000000000000960d2228e999&edition=fr-fr&query=France&hard_limit=20";
//        $url_deforestation = "https://api.ozae.com/gnw/articles?date=20180105__20181212&key=c5c6c39f1c25452c9e735812468879c8&edition=fr-fr&query=France&hard_limit=100";



        $timeout = 100;
        $content_pays = [];


        //article lié a la France
        try {
            $ch = curl_init($url_pollution);

            // Check if initialization had gone wrong*
            if ($ch === false) {
                $event->setControllerResult('failed to initialize');
            }

            curl_setopt($ch, CURLOPT_HEADER, false);


            curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


            $content_pays = json_decode(curl_exec($ch), true);

            // Check the return value of curl_exec(), too
            if ($content_pays === false) {
                $event->setControllerResult(curl_error($ch));
            }

            /* Process $content here */
            $event->setControllerResult(curl_error($ch));

            // Close curl handle
            curl_close($ch);
        } catch(Exception $e) {
            $event->setControllerResult('Curl failed with error');
        }


        //liste de tout les article contenant 1 mot
        $list_id = [];

        if (!empty($content_pays["articles"])){

            foreach ($content_pays["articles"] as $article){
                array_push($list_id,$article['id']);
            }
        }

//        contenu article par id
//        https://api.ozae.com/gnw/article/{id}/html_content?key=8dff35cfd68b48be8dff4c6a2d0fb3ac

        $raw_data_content=[];
        foreach ($list_id as $article_id){

            try {

                $url = "https://api.ozae.com/gnw/article/".$article_id."/html_content?key=c5c6c39f1c25452c9e735812468879c8";

                $ch = curl_init($url);

                // Check if initialization had gone wrong*
                if ($ch === false) {
                    $event->setControllerResult('failed to initialize');
                }

                curl_setopt($ch, CURLOPT_HEADER, false);


                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


                $content = curl_exec($ch);

                array_push($raw_data_content,$content);

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


        }

        //ajoute l'ensemble des contenu dans une variable
        $raw_data_content_implode = implode($raw_data_content);


//        recherche de mot clé et leur nombre de occurrence  (atuellement les requete sont trop importante et cela met trop de temps "timeour")
//        $keywordsFr = array('déforestations','pollution','erosions','appauvrissement des sols','déchets urbain','pollution urbaine','pollution atmosphérique','catastrophe nucléaire','déchets marins','marée noire');
//        $keywordsUs = array('deforestation ','pollution',' erosions', 'soil depletion', 'urban waste', 'urban pollution', 'air pollution', 'nuclear disaster', 'marine litter', 'oil spill');
//        $number_key_words_occurence=[];
//
//        $test = substr_count($raw_data_content_implode, 'Trump');
//        $occurence = [];
//        foreach($keywordsUs as $cle=>$keyword){
//            $test = substr_count($raw_data_content_implode, $keyword);
//            array_push($occurence, [$keyword => $test]);
//
//        }

        $pollution = substr_count($raw_data_content_implode, "pollution");
        $deforestation = 1;


        
        $response = ["Pollution", "Déforestation"];



//        $event->setControllerResult(gettype($content["articles"]));
//        $event->setControllerResult($list_id);
//        $event->setControllerResult($raw_data_content_implode);
//        $event->setControllerResult($test);
//        $event->setControllerResult($occurence);
        $event->setControllerResult($pollution);
        $event->setControllerResult($response);
    }
}