<?php

namespace App\DataFixtures\Product;

use App\DataFixtures\AbstractFixture;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends AbstractFixture
{
    private string $name;

    private int $price;

    private Product $product;

    public function load(ObjectManager $manager): void
    {
        $product = new Product(
            name: $this->name ?? $this->faker->name,
            price: $this->price ?? $this->faker->numberBetween(100, 1000),
        );

        $manager->persist($product);
        $manager->flush();

        $this->product = $product;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }
}
