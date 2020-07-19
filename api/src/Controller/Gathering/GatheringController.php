<?php

declare(strict_types=1);

/*
 * This file is part of a Salah Medfay project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Gathering;

use App\Controller\BaseController;
use RiotAPI\LeagueAPI\Exceptions\GeneralException;
use RiotAPI\LeagueAPI\Objects\LeagueListDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GatheringController extends BaseController
{
    private const RANKED_SOLO_QUEUE = 'RANKED_SOLO_5x5';

    /**
     * @Route("/gathering/challenger-summoners", name="get_challenger_summoners")
     *
     * @throws GeneralException
     */
    public function index(): JsonResponse
    {
        try {
            $champion = $this->leagueApi->getLeagueChallenger(self::RANKED_SOLO_QUEUE);
        } catch (GeneralException $e) {
            throw new GeneralException($e->getMessage());
        }

        /* @var LeagueListDto $champion */
        return new JsonResponse($champion);
    }
}
