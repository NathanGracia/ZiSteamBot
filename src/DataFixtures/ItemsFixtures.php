<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Item;

class ItemsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 10; $i++){
            $item = new Item();
            $item->setName("Nom de l'item numero ".$i)
                 ->setPrice(10)   
                 ->setImage("https://d3isma7snj3lcx.cloudfront.net/optim/images/gallery/92/92399/rust-pc-c64f60e5__220_220__7-11-646-650.png")
                 ->setCreatedAt(new \DateTime());
            $manager->persist($item);
            
        }
        $manager->flush();
    }
}
