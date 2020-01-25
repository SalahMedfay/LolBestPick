<?php

declare(strict_types=1);

/*
 * This file is part of a Salah Medfay project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use App\Service\LeagueApi;

class BaseController extends AbstractFOSRestController
{
    /** @var LeagueApi */
    protected $leagueApi;

    /**
     * BaseController constructor.
     *
     * @param $leagueApi
     */
    public function __construct(LeagueApi $leagueApi)
    {
        $this->leagueApi = $leagueApi;
    }
}
