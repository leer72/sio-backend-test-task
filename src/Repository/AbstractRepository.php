<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class AbstractRepository extends ServiceEntityRepository
{
    public function flush(): void
    {
        $this->_em->flush();
    }
}
