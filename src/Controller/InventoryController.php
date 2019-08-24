<?php


namespace App\Controller;


use App\Entity\Inventory;
use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InventoryController extends AbstractController
{
    /**
     * @Route("/inventory", name="inventory")
     */
    public function inventory(ItemRepository $repo)
    {
        $items = $this->getInventory($repo);

        return $this->render('blog/inventory.html.twig', ["items"=>$items
        ]);


    }

    public function getInventory(ItemRepository $repo)
    {
        $inventory = new Inventory();
        $url = "https://steamcommunity.com/id/deuskiwi/inventory/json/252490/2";

            $response = json_decode( file_get_contents($url), true);


        $itemNames = $response["rgDescriptions"];
        $items = [];
        foreach ($itemNames as $itemName){

            if(!empty($itemName["name"])){
                $item = $repo->findOneByName($itemName["name"]);
            }
            if(!empty($item)){
                $inventory->addItem($item);
                $items[] = $item;
            }






        }
        return $items;

    }
}