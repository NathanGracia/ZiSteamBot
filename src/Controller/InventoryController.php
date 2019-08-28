<?php


namespace App\Controller;


use App\Entity\Inventory;
use App\Entity\InventoryItem;
use App\Entity\Item;
use App\Repository\InventoryItemRepository;
use App\Repository\InventoryRepository;
use App\Repository\ItemRepository;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InventoryController extends AbstractController
{
    const INVENTORY_USERNAME = "DeusKiwi";
    /**
     * @Route("/inventory", name="inventory")
     */
    public function inventory(ItemRepository $itemRepository, InventoryItemRepository $inventoryItemRepository, Request $request, ObjectManager $manager, InventoryRepository $inventoryRepository)
    {
        $inventory = $this->getInventory($itemRepository, $inventoryItemRepository, $request, $manager, $inventoryRepository);

        $inventoryItems = $inventory->getItems();
        $item = new InventoryItem();


        return $this->render('blog/inventory.html.twig', ["inventoryItems" => $inventoryItems
        ]);


    }

    public function getInventory(ItemRepository $repo, InventoryItemRepository $inventoryItemRepo, Request $request, ObjectManager $manager, InventoryRepository $inventoryRepository)
    {
        $inventory = $inventoryRepository->findOneByUsername(self::INVENTORY_USERNAME);
        if(empty($inventory)){
            $inventory = new Inventory();
            $inventory->setUsername(self::INVENTORY_USERNAME);
        }
        $url = "https://steamcommunity.com/id/deuskiwi/inventory/json/252490/2";

        $response = json_decode(file_get_contents($url), true);


        $itemNames = $response["rgDescriptions"];
        $items = [];
        foreach ($itemNames as $itemName) {

            if (!empty($itemName["name"])) {
                /** @var Item $item */
                //TODO mtrouver l'invetaire à l'aide de l'id pas de l'objet item ( qui change tout le tempsà cause du prix)
                $item = $repo->findOneByName($itemName["name"]);
                /** @var InventoryItem $inventoryItem */
                $inventoryItem = $inventoryItemRepo->findOneByItem($item);
                if (!empty($item)) {
                    if (!empty($inventoryItem)) {


                        $inventoryItem->setidItem($item->getId());
                        $inventoryItem->setItem($item);

                        $inventory->addItem($inventoryItem);


                    } else {
                        $inventoryItem = new InventoryItem();
                        $inventoryItem->setidItem($item->getId());
                        $inventoryItem->setItem($item);
                        $inventory->addItem($inventoryItem);

                    }
                }

            }

        }

        $manager->persist($inventory);
        $manager->flush();
        return $inventory;

    }
}