<?php

declare(strict_types=1);

/*
 * This file is part of a Salah Medfay project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\Manager\GatheringManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LbpGatherChampionsCommand extends Command
{
    protected static $defaultName = 'lbp:gather:champions';
    protected $gatheringManager;

    /**
     * LbpGatherCommand constructor.
     *
     * @param $gatheringManager
     */
    public function __construct(GatheringManager $gatheringManager)
    {
        $this->gatheringManager = $gatheringManager;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this
            ->setDescription('Gather champions list from Riot API')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $totalChampions = $this->gatheringManager->gatherChampions();
        $io             = new SymfonyStyle($input, $output);

        $io->success($totalChampions.' champions saved.');

        return 0;
    }
}
