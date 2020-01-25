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

class LbpGatherCommand extends Command
{
    protected static $defaultName = 'lbp:gather:data';
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
            ->setDescription('Gather data from Riot API')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io            = new SymfonyStyle($input, $output);
        $leagueEntries = $this->gatheringManager->getLeagueChallenger();
        if (null !== $leagueEntries) {
            $io->text(sprintf('There is %s league challenger', count($leagueEntries->getData()['entries'])));
            $io->createProgressBar(count($leagueEntries->getData()['entries']));
            $io->progressStart();
            foreach ($leagueEntries->getData()['entries'] as $challengerSummoner) {
                $this->gatheringManager->gatherSummoners($challengerSummoner['summonerId']);
                $io->progressAdvance();
            }
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
