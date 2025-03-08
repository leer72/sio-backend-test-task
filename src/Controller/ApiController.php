<?php

namespace App\Controller;

use App\DTO\CalculatePriceDTO;
use App\DTO\PurchaseDTO;
use App\Facade\CalculationFacade;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    public function __construct(
        private readonly CalculationFacade $calculationFacade,
    ) {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/calculate-price', name: 'app_calculate_price', methods: ['POST'])]
    public function calculatePrice(CalculatePriceDTO $calculatePriceDTO): JsonResponse
    {
        try {
            $price = $this->calculationFacade->getFinalProductPrice(
                productId: $calculatePriceDTO->productId,
                couponCode: $calculatePriceDTO->couponCode,
                taxNumber: $calculatePriceDTO->taxNumber,
            );
        } catch (Exception $exception) {
            throw new BadRequestHttpException(
                message: $exception->getMessage(),
            );
        }

        return new JsonResponse(
            data: ['price' => $price],
        );
    }

//    #[Route(path: '/purchase', name: 'app_purchase', methods: ['POST'])]
//    public function purchase(PurchaseDTO $purchaseDTO): JsonResponse
//    {
//
//    }
}
