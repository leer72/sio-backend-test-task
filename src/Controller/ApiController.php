<?php

namespace App\Controller;

use App\DTO\CalculatePriceDTO;
use App\DTO\PurchaseDTO;
use App\Enum\PaymentProcessors;
use App\Facade\CalculationFacade;
use App\Repository\Coupon\CouponRepository;
use App\Repository\Product\ProductRepository;
use App\Service\Coupon\CouponService;
use App\Service\Payment\PaymentService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiController extends AbstractController
{
    public function __construct(
        private readonly CalculationFacade $calculationFacade,
        private readonly ProductRepository $productRepository,
        private readonly CouponRepository $couponRepository,
        private readonly TranslatorInterface $translator,
    ) {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/calculate-price', name: 'app_calculate_price', methods: ['POST'])]
    public function calculatePrice(
        #[MapRequestPayload] CalculatePriceDTO $calculatePriceDTO,
    ): JsonResponse {
        try {
            return new JsonResponse(
                data: [
                    'price' => $this->calculationFacade->getFinalProductPrice(
                        productPrice: $this->productRepository->getById($calculatePriceDTO->product)->getPrice(),
                        coupon: is_null($calculatePriceDTO->couponCode)
                            ? null
                            : $this->couponRepository->getByValueAndType(
                                value: CouponService::getCouponValue($calculatePriceDTO->couponCode),
                                couponType: CouponService::getType($calculatePriceDTO->couponCode),
                            ),
                        taxNumber: $calculatePriceDTO->taxNumber,
                    )
                ],
            );
        } catch (Exception $exception) {
            throw new BadRequestHttpException($this->translator->trans($exception->getMessage()));
        }

    }

    /**
     * @throws Exception
     */
    #[Route(path: '/purchase', name: 'app_purchase', methods: ['POST'])]
    public function purchase(
        #[MapRequestPayload] PurchaseDTO $purchaseDTO,
        PaymentService $paymentService,
    ): JsonResponse {
        try {
            $price = $this->calculationFacade->getFinalProductPrice(
                productPrice: $this->productRepository->getById($purchaseDTO->product)->getPrice(),
                coupon: is_null($purchaseDTO->couponCode)
                    ? null
                    : $this->couponRepository->getByValueAndType(
                        value: CouponService::getCouponValue($purchaseDTO->couponCode),
                        couponType: CouponService::getType($purchaseDTO->couponCode),
                    ),
                taxNumber: $purchaseDTO->taxNumber,
            );

            $paymentService->process(
                paymentType: PaymentProcessors::from($purchaseDTO->paymentProcessor),
                price: $price,
            );
        } catch (Exception $exception) {
            throw new BadRequestHttpException(message: $this->translator->trans($exception->getMessage()));
        }

        return new JsonResponse(
            data: [
                'success' => true,
                'price' => $price,
            ],
        );
    }
}
