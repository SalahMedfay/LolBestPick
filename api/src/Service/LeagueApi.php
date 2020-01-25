<?php

declare(strict_types=1);

/*
 * This file is part of a Salah Medfay project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use RiotAPI\LeagueAPI\Exceptions\GeneralException;
use RiotAPI\LeagueAPI\Exceptions\SettingsException;
use RiotAPI\LeagueAPI\LeagueAPI as BaseLeagueAPI;

class LeagueApi extends BaseLeagueAPI
{
    private $key;
    private $region;

    /**
     * LeagueApi constructor.
     *
     * @param $key
     * @param $region
     *
     * @throws GeneralException
     * @throws SettingsException
     */
    public function __construct($key, $region)
    {
        $this->key    = $key;
        $this->region = $region;
        parent::__construct([BaseLeagueAPI::SET_KEY => $this->key, BaseLeagueAPI::SET_REGION => $this->region]);
    }
}
