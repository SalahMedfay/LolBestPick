<?php

declare(strict_types=1);

/*
 * This file is part of a Salah Medfay project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Vs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Vs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vs[]    findAll()
 * @method Vs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GatheringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vs::class);
    }
}
