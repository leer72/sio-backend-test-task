<?php

namespace App\Controller;

use App\ArgumentResolver\CalculatePriceDTOResolver;
use App\ArgumentResolver\PurchaseDTOResolver;
use App\DTO\CalculatePriceDTO;
use App\DTO\PurchaseDTO;
use App\Exception\PaymentException;
use App\Facade\CalculationFacade;
use App\Service\Payment\PaymentService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
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
    public function calculatePrice(
        #[ValueResolver(CalculatePriceDTOResolver::class)]
        CalculatePriceDTO $calculatePriceDTO,
    ): JsonResponse {
        $price = $this->getPrice($calculatePriceDTO);

        return new JsonResponse(
            data: ['price' => $price],
        );
    }

    #[Route(path: '/purchase', name: 'app_purchase', methods: ['POST'])]
    public function purchase(
        #[ValueResolver(PurchaseDTOResolver::class)]
        PurchaseDTO $purchaseDTO,
        PaymentService $paymentService
    ): JsonResponse {
        $price = $this->getPrice(new CalculatePriceDTO(
            productId: $purchaseDTO->productId,
            taxNumber: $purchaseDTO->taxNumber,
            couponCode: $purchaseDTO->couponCode,
        ));

        try {
            $paymentService->process(
                paymentType: $purchaseDTO->paymentProcessor,
                price: $price,
            );
        } catch (PaymentException $exception) {
            throw new BadRequestHttpException(message: $exception->getMessage());
        }

        return new JsonResponse(
            data: [
                'success' => true,
                'price' => $price,
            ],
        );
    }

    private function getPrice(CalculatePriceDTO $calculatePriceDTO): float
    {
        try {
            return $this->calculationFacade->getFinalProductPrice(
                productId: $calculatePriceDTO->productId,
                couponCode: $calculatePriceDTO->couponCode,
                taxNumber: $calculatePriceDTO->taxNumber,
            );
        } catch (Exception $exception) {
            throw new BadRequestHttpException(
                message: $exception->getMessage(),
            );
        }
    }
}
