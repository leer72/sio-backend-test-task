<?php

namespace App\Controller;

use App\DTO\CalculatePriceDTO;
use App\DTO\PurchaseDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route(path: '/calculate-price', name: 'app_calculate_price', methods: ['POST'])]
    public function calculatePrice(CalculatePriceDTO $calculatePriceDTO)
    {

    }

    #[Route(path: '/purchase', name: 'app_purchase', methods: ['POST'])]
    public function purchase(PurchaseDTO $purchaseDTO)
    {

    }
}
