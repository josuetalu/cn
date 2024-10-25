<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Delivery;
use App\Entity\Bin;
use App\Entity\Bank;
use App\Entity\OutOfService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/dashboard")
 */
class DashboardController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="dashboard")
     */
    public function index(): Response
    {
        $userCount = $this->entityManager->getRepository(User::class)->count([]);
        $orderCount = $this->entityManager->getRepository(Order::class)->count([]);
        $deliveryCount = $this->entityManager->getRepository(Delivery::class)->count([]);
        $binCount = $this->entityManager->getRepository(Bin::class)->count([]);
        $bankCount = $this->entityManager->getRepository(Bank::class)->count([]);
        $outOfServiceCount = $this->entityManager->getRepository(OutOfService::class)->count([]);

        return $this->render('dashboard/index.html.twig', [
            'userCount' => $userCount,
            'orderCount' => $orderCount,
            'deliveryCount' => $deliveryCount,
            'binCount' => $binCount,
            'bankCount' => $bankCount,
        ]);
    }
}