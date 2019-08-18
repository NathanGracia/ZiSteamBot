<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\ItemRepository;
use App\Repository\PriceHistoryRepository;
use App\Entity\Item;
use App\Entity\PriceHistory;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\LineChart;


class BlogController extends AbstractController
{
const RUST_ID = "252490";
    /**
     * @Route("/items/{orderBy}", name="items")
     */
    public function items(ItemRepository $repo,ObjectManager $manager, string $orderBy = 'name')
    {
        $repo = $this->getDoctrine()->getRepository(Item::class);
        switch ($orderBy){
            case 'nameASC':

                $items = $repo->findby(array(),array('name'=>'ASC'));
                break;
            case 'nameDESC':

                $items = $repo->findby(array(),array('name'=>'DESC'));
                break;
            case 'priceASC':

                $items = $repo->findby(array(),array('actualPrice'=>'ASC'));
                break;
            case 'priceDESC':

                $items = $repo->findby(array(),array('actualPrice'=>'DESC'));
                break;
            case 'lastIncrease':

                $items = $repo->findby(array(),array('lastIncrease'=>'DESC'));
                break;
            default:
                $items = $repo->findby(array(),array('name'=>'ASC'));
        }


        
        
        
        return $this->render('blog/itemList.html.twig', [
            'controller_name' => 'BlogController',
            'items' => $items
        ]);
       

    }
    /**
     * @Route("/nouvelleRequette", name="newRequest")
     */
    public function newRequest(ItemRepository $repo,ObjectManager $manager, PriceHistoryRepository $priceHistoryRepository)
    {   $start = 0;
         /*while($start < 1900){
            $url = "/market/search/render?norender=1&q=&currency=3&category_252490_itemclass%5B%5D=any&appid=252490&count=100&start=".$start;
            $this->getItemsFromApi($url,$repo,$manager,$priceHistoryRepository);
            $start += 100;
        }
         */
         $url="/market/search/render?norender=1&q=&currency=3&category_252490_itemclass%5B%5D=any&appid=252490&q=moon+light";
        $this->getItemsFromApi($url,$repo,$manager,$priceHistoryRepository);

        return($this->redirectToRoute('items'));
       

    }
    /**
     * @Route("/nouvelleRequetteDetaillée/{idItem}", name="newDetailedRequest")
     */
    public function newDetailedRequest(string $idItem,ItemRepository $repo,ObjectManager $manager){
        //get item
        $item = $repo->findOneById($idItem);


        $url = $this->generateDetailedSteamLink($item->getName());
        //decode result / get out the "$" from median price
        $response =json_decode(  file_get_contents($url), true);
        $median = str_replace("$","",$response["median_price"]);
        //setters
        $item->setMedianPrice(floatval($median))
             ->setVolume(floatval($response["volume"]));

        //save
        $manager->persist($item);
        $manager->flush();
        return($this->redirectToRoute('itemDetail',array('id'=>$idItem)));
    }
    /**
     * @Route("/item/{id}", name="itemDetail")
     */
    public function itemDetail($id,ItemRepository $repoItem,ObjectManager $manager,PriceHistoryRepository $repoPriceHistory)
    {
   
        $item = $repoItem->find($id);

        $prices = $repoPriceHistory->findByIdItem($id,['id'=>'ASC']);
        $prices = array_reverse($prices);
        $dataChart[] = ["Date","Prix"];
        foreach($prices as $price){
            $priceData  = [$price->getDate(),$price->getPrice()];
            $dataChart[] = $priceData;
        }
         //Creation du graphique
        $lineChart = new LineChart();
     
        $lineChart->getData()->setArrayToDataTable($dataChart);
        $lineChart->getOptions()->setTitle($item->getName());
        $lineChart->getOptions()->setHeight(500);
        $lineChart->getOptions()->setWidth(900);
        $lineChart->getOptions()->setBackgroundColor('black');
        $lineChart->getOptions()->getTitleTextStyle()->setBold(true);
        $lineChart->getOptions()->setLineWidth(5);
        $lineChart->getOptions()->getVAxis()->getGridlines()->setCount(0);
        $lineChart->getOptions()->getHAxis()->getGridlines()->setCount(3);
        $lineChart->getOptions()->getTitleTextStyle()->setColor('white');
        $lineChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $lineChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $lineChart->getOptions()->getTitleTextStyle()->setFontSize(20);
            

        return $this->render('blog/itemDetail.html.twig', [
            'item' => $item,
            'prices' => $prices,
            'lineChart' => $lineChart,
       
        ]);
       

    }
    /**
     * @Route("/",name="home" )
     */
    public function home(){
       
        return $this->render('blog/home.html.twig',[
            'title'=>"Yop yop"
            
        ]);
    }
    public function requestApi(String $request){
        $url = "https://steamcommunity.com";
        $fullUrl = $url.$request;

        return file_get_contents($fullUrl);
    }
    public function getItemsFromApi(String $requestUrl,ItemRepository $repo,ObjectManager $manager,PriceHistoryRepository $repoPriceHistory){
        //envoie de la reqUËTE
        $response =json_decode( $this->requestApi($requestUrl), true);
        $resultItems = $response["results"];

        foreach ($resultItems as $item){

            $existingItem = $repo->findOneByName($item["name"]);
            if(is_null($existingItem)){
        
              //creation d'un nouvel item si il n'existe pas
                $newItem = new Item();
                $newItem->setName($item["name"])
                        ->setImage("https://steamcommunity-a.akamaihd.net/economy/image/".$item["asset_description"]["icon_url"])
                        ->setActualPrice($item["sell_price"]/100)
                        ->setCreatedAt(new \DateTime())
                        ->setSteamLink($this->generateSteamLink($item["name"]));
                //on envoie en bdd pour recuperer un id item (pour le price history)
                $manager->persist($newItem);
                $manager->flush();
                
                $lastItem = $repo->findOneByName($item["name"]);
                //création du price history
                $newPriceHistory = new PriceHistory();
                $newPriceHistory->setPrice($lastItem->getActualPrice())
                                ->setIdItem($lastItem->getId())
                                ->setDate(new \DateTime());

                $manager->persist($newPriceHistory);
            }else{
                //l'item existe deja
                $existingItem->setImage("https://steamcommunity-a.akamaihd.net/economy/image/".$item["asset_description"]["icon_url"])
                             ->setActualPrice($item["sell_price"]/100);
                $newPriceHistory = new PriceHistory();
                 //création du price history si le prix n'est pas le meme
                $oldPrice = $repoPriceHistory->findOneByIdItem($existingItem->getId(),array("date" => "DESC"))->getPrice();
                var_dump($item);die;
                if($oldPrice != $item["sell_price"]/100 ){
                $newPriceHistory->setPrice($item["sell_price"]/100)
                                ->setIdItem($existingItem->getId())
                                ->setDate(new \DateTime());
                //increase rate for item
                $existingItem->setLastIncrease($this->calculateLastIncrease($oldPrice, $item["sell_price"]/100));

                $manager->persist($existingItem);
                $manager->persist($newPriceHistory);}
            };
            
       
        }
        $manager->flush();
    }
    public function calculateLastIncrease($oldprice, $newprice){

        $lastIncrease = (($newprice-$oldprice)/$oldprice)*100;
        return $lastIncrease;
    }
    public function generateSteamLink(string $name){

        $nameForUrl = str_replace(" ","%20",$name);
        $preUrl = "https://steamcommunity.com/market/listings";
        $rustUrl = "/".$this::RUST_ID;
        $steamLink= $preUrl . $rustUrl . "/" .$nameForUrl;
        return $steamLink;
    }
    public function generateDetailedSteamLink(string $name){
        $url="";
        $nameForUrl = str_replace(" ","%20",$name);
        $preUrl = "https://steamcommunity.com/market/priceoverview/?currency=1&appid=252490&market_hash_name=";

        $steamLink= $preUrl .$nameForUrl;
        return $steamLink;
    }
}
