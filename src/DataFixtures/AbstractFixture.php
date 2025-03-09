<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Faker\Generator;


abstract class AbstractFixture extends Fixture
{
    private const string LOCALE = 'ru_RU';

    protected Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create(self::LOCALE);
    }
}
