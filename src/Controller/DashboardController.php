<?php

namespace App\Controller;

use App\Entity\Buy;
use App\Repository\BuyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/tableau", name="dashboard")
     */
    public function index(BuyRepository $buyRepository)
    {
        $buy = new Buy();

        var_dump($buys);
        return $this->render('blog/dashboard.html.twig', array());
    }


}
