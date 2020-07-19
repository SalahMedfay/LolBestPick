<?php

declare(strict_types=1);

/*
 * This file is part of a Salah Medfay project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Manager;

use App\Entity\Champion;
use App\Entity\Game;
use App\Entity\Mate;
use App\Entity\Vs;
use App\Service\DataDragonApi;
use App\Service\LeagueApi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use RiotAPI\DataDragonAPI\Exceptions\ArgumentException;
use RiotAPI\DataDragonAPI\Exceptions\SettingsException;
use RiotAPI\LeagueAPI\Exceptions\GeneralException;
use RiotAPI\LeagueAPI\Exceptions\RequestException;
use RiotAPI\LeagueAPI\Exceptions\ServerException;
use RiotAPI\LeagueAPI\Exceptions\ServerLimitException;
use RiotAPI\LeagueAPI\Objects\LeagueListDto;
use RiotAPI\LeagueAPI\Objects\MatchDto;
use RiotAPI\LeagueAPI\Objects\MatchlistDto;
use RiotAPI\LeagueAPI\Objects\SummonerDto;

class GatheringManager extends BaseManager
{
    private $championManager;
    private $mateManager;
    private $vsManager;
    private $gameManager;

    public function __construct(
        ChampionManager $championManager,
        MateManager $mateManager,
        VsManager $vsManager,
        GameManager $gameManager,
        LeagueApi $leagueApi,
        DataDragonApi $dataDragonAPI,
        EntityManagerInterface $manager,
        ServiceEntityRepository $repository
    ) {
        $this->championManager   = $championManager;
        $this->mateManager       = $mateManager;
        $this->vsManager         = $vsManager;
        $this->gameManager       = $gameManager;
        parent::__construct($leagueApi, $dataDragonAPI, $manager, $repository);
    }

    final public function gatherSummoners(string $challengerSummoner): void
    {
        // Get summonerDTO info
        $summoner = $this->getSummoner($challengerSummoner);
        // Get match lists of a summoner.
        $matches = $this->getMatchListBySummoner($summoner);

        if (isset($matches)) {
            foreach ($matches as $match) {
                $winData  = [];
                try {
                    $game = $this->leagueApi->getMatch($match->gameId);
                } catch (RequestException $e) {
                } catch (ServerException $e) {
                } catch (ServerLimitException $e) {
                } catch (\RiotAPI\LeagueAPI\Exceptions\SettingsException $e) {
                } catch (GeneralException $e) {
                }
                if (isset($game)) {
                    if (null !== $game && !empty($this->gameManager->repository->findBy(['riotGameId' => $game->gameId]))) {
                        continue;
                    }
                    $gameToTreat = new Game();
                    $gameToTreat->setRiotGameId($game->gameId);
                    try {
                        $this->manager->persist($gameToTreat);
                        $this->manager->flush();
                    } catch (ORMException $e) {
                    }

                    if (100 === $game->teams[0]->teamId) {
                        if ('Win' === $game->teams[0]->win) {
                            $winnerTeam = 100; // blue
                        } else {
                            $winnerTeam = 200; // red
                        }
                    } else {
                        if ('Win' === $game->teams[0]->win) {
                            $winnerTeam = 200; // red
                        } else {
                            $winnerTeam = 100; // blue
                        }
                    }

                    foreach ($game->participants as $participant) {
                        if ($winnerTeam === $participant->teamId) {
                            $winData['win'][] = $participant->championId;
                        } else {
                            $winData['lose'][] = $participant->championId;
                        }
                    }
                    foreach ($winData['lose'] as $i => $data) {
                        [$j, $dataTwo, $with] = $this->MateLose($winData['lose'], $data, $game);
                        [$j, $dataTwo, $vs]   = $this->VsLose($winData['win'], $data);
                        unset($j, $i, $data, $dataTwo);
                    }

                    foreach ($winData['win'] as $i => $data) {
                        [$j, $dataTwo] = $this->MateWin($winData['win'], $data, $game);
                        [$j, $dataTwo] = $this->VsWin($winData['lose'], $data);
                        unset($j, $i, $data);
                    }
                }
            }
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    final public function gatherChampions(): int
    {
        $sum = 0;
        try {
            $this->dataDragonAPI::initByApi($this->leagueApi);
        } catch (RequestException $e) {
        } catch (ServerException $e) {
        }
        try {
            $champions = $this->dataDragonAPI::getStaticChampions();
        } catch (ArgumentException $e) {
        } catch (SettingsException $e) {
        }

        if (isset($champions) && array_key_exists('data', $champions)) {
            foreach ($champions['data'] as $champion) {
                if (null === $this->championManager->repository->findOneBy(['reference' => $champion['key']])) {
                    $oChampion = new Champion();
                    $oChampion->setName($champion['id'])
                    ->setReference($champion['key']);
                    $this->championManager->manager->persist($oChampion);
                    ++$sum;
                }
            }
        }

        $this->championManager->manager->flush();

        return $sum;
    }

    final public function getLeagueChallenger(): ?LeagueListDto
    {
        // Get challenger summoners list
        try {
            $leagueEntries = $this->leagueApi->getLeagueChallenger(self::RANKED_SOLO_QUEUE);
        } catch (RequestException $e) {
        } catch (ServerException $e) {
        } catch (ServerLimitException $e) {
        } catch (\RiotAPI\LeagueAPI\Exceptions\SettingsException $e) {
        } catch (GeneralException $e) {
        }

        if (isset($leagueEntries)) {
            return $leagueEntries;
        }
    }

    final public function getSummoner(string $challengerSummoner): ?SummonerDto
    {
        try {
            $summoner = $this->leagueApi->getSummoner($challengerSummoner);
        } catch (RequestException $e) {
        } catch (ServerException $e) {
        } catch (ServerLimitException $e) {
        } catch (\RiotAPI\LeagueAPI\Exceptions\SettingsException $e) {
        } catch (GeneralException $e) {
        }

        if (isset($summoner)) {
            return $summoner;
        }
    }

    final public function getMatchListBySummoner(?SummonerDto $summoner): MatchlistDto
    {
        try {
            if (isset($summoner)) {
                $matches = $this->leagueApi->getMatchlistByAccount($summoner->getData()['accountId']);
            }
        } catch (RequestException $e) {
        } catch (ServerException $e) {
        } catch (ServerLimitException $e) {
        } catch (\RiotAPI\LeagueAPI\Exceptions\SettingsException $e) {
        } catch (GeneralException $e) {
        }

        if (isset($matches)) {
            return $matches;
        }
    }

    /**
     * @param $lose
     * @param $data
     */
    final public function MateLose($lose, $data, ?MatchDto $game): array
    {
        foreach ($lose as $j => $dataTwo) {
            if ($data !== $dataTwo) {
                if (($with = $this->mateManager->repository->findOneBy(
                        [
                            'firstChampion' => $this->championManager->repository->findOneBy(['reference' => $data]),
                            'secondChampion' => $this->championManager->repository->findOneBy(['reference' => $dataTwo]),
                        ]
                    )) || ($with = $this->mateManager->repository->findOneBy(
                        [
                            'secondChampion' => $this->championManager->repository->findOneBy(['reference' => $data]),
                            'firstChampion' => $this->championManager->repository->findOneBy(['reference' => $dataTwo]),
                        ]
                    ))) {
                    if (isset($game) && $this->gameManager->repository->findOneBy([], ['id' => 'DESC'])->getRiotGameId() === $game->gameId) {
                        continue;
                    }
                    $with->setLose($with->getLose() + 1);
                } else {
                    $with = new Mate();
                    $with->setFirstChampion($this->championManager->repository->findOneBy(['reference' => $data]))
                        ->setSecondChampion($this->championManager->repository->findOneBy(['reference' => $dataTwo]))
                        ->setLose(1)
                        ->setWin(0);
                }
                try {
                    $this->manager->persist($with);
                    $this->manager->flush();
                } catch (ORMException $e) {
                }
            }
            sleep(1);
        }

        return [$j, $dataTwo, $with];
    }

    /**
     * @param $win
     * @param $data
     */
    public function VsLose($win, $data): array
    {
        foreach ($win as $j => $dataTwo) {
            if ($data !== $dataTwo) {
                if ($vs = $this->vsManager->repository->findOneBy(
                    [
                        'firstChampion' => $this->championManager->repository->findOneBy(['reference' => $data]),
                        'secondChampion' => $this->championManager->repository->findOneBy(['reference' => $dataTwo]),
                    ]
                )) {
                    $vs->setLose($vs->getLose() + 1);
                } else {
                    $vs = new Vs();
                    $vs->setFirstChampion($this->championManager->repository->findOneBy(['reference' => $data]))
                        ->setSecondChampion($this->championManager->repository->findOneBy(['reference' => $dataTwo]))
                        ->setLose(1)
                        ->setWin(0);
                }
                try {
                    $this->manager->persist($vs);
                    $this->manager->flush();
                } catch (ORMException $e) {
                }
            }
            sleep(1);
        }

        return [$j, $dataTwo, $vs];
    }

    /**
     * @param $win
     * @param $data
     */
    public function MateWin($win, $data, ?MatchDto $game): array
    {
        foreach ($win as $j => $dataTwo) {
            if ($data !== $dataTwo) {
                if (($with = $this->mateManager->repository->findOneBy(
                        [
                            'firstChampion' => $this->championManager->repository->findOneBy(['reference' => $data]),
                            'secondChampion' => $this->championManager->repository->findOneBy(['reference' => $dataTwo]),
                        ]
                    )) || ($with = $this->mateManager->repository->findOneBy(
                        [
                            'secondChampion' => $this->championManager->repository->findOneBy(['reference' => $data]),
                            'firstChampion' => $this->championManager->repository->findOneBy(['reference' => $dataTwo]),
                        ]
                    ))) {
                    if ($this->gameManager->repository->findOneBy([], ['id' => 'DESC'])->getRiotGameId() === $game->gameId) {
                        continue;
                    }
                    $with->setWin($with->getWin() + 1);
                } else {
                    $with = new Mate();
                    $with->setFirstChampion($this->championManager->repository->findOneBy(['reference' => $data]))
                        ->setSecondChampion($this->championManager->repository->findOneBy(['reference' => $dataTwo]))
                        ->setWin(1)
                        ->setLose(0);
                }
                try {
                    $this->manager->persist($with);
                    $this->manager->flush();
                } catch (ORMException $e) {
                }
            }
            sleep(1);
        }

        return [$j, $dataTwo];
    }

    /**
     * @param $lose
     * @param $data
     */
    public function VsWin($lose, $data): array
    {
        foreach ($lose as $j => $dataTwo) {
            if ($data !== $dataTwo) {
                if ($vs = $this->vsManager->repository->findOneBy(
                    [
                        'firstChampion' => $this->championManager->repository->findOneBy(['reference' => $data]),
                        'secondChampion' => $this->championManager->repository->findOneBy(['reference' => $dataTwo]),
                    ]
                )) {
                    $vs->setWin($vs->getWin() + 1);
                } else {
                    $vs = new Vs();
                    $vs->setFirstChampion($this->championManager->repository->findOneBy(['reference' => $data]))
                        ->setSecondChampion($this->championManager->repository->findOneBy(['reference' => $dataTwo]))
                        ->setWin(1)
                        ->setLose(0);
                }
                try {
                    $this->manager->persist($vs);
                    $this->manager->flush();
                } catch (ORMException $e) {
                }
            }
            sleep(1);
        }

        return [$j, $dataTwo];
    }
}
