<?php

declare(strict_types=1);

/*
 * This file is part of a Salah Medfay project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Manager;

use App\Service\LeagueApi;
use App\Service\DataDragonApi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class BaseManager
{
    protected const RANKED_SOLO_QUEUE = 'RANKED_SOLO_5x5';

    /** @var ServiceEntityRepository */
    protected $repository;

    /** @var EntityManager */
    protected $manager;

    /** @var string */
    protected $docClassname;

    /** @var LeagueApi */
    protected $leagueApi;

    /** @var DataDragonApi */
    protected $dataDragonAPI;

    /**
     * BaseController constructor.
     */
    public function __construct(
    LeagueApi $leagueApi,
    DataDragonApi $dataDragonAPI,
    EntityManagerInterface $manager,
    ServiceEntityRepository $repository
    ) {
        $this->leagueApi       = $leagueApi;
        $this->dataDragonAPI   = $dataDragonAPI;
        $this->manager         = $manager;
        $this->repository      = $repository;
    }
}
