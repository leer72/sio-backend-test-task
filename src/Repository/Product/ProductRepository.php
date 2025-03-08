<?php

namespace App\Repository\Product;

use App\Entity\Product;
use App\Repository\AbstractRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getById(int $id): Product
    {
        $product = $this->find($id);

        if (is_null($product)) {
            throw new EntityNotFoundException(
                message: 'Product not found',
            );
        }

        return $product;
    }
}
