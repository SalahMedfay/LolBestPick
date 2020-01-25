<?php

declare(strict_types=1);

/*
 * This file is part of a Salah Medfay project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $riotGameId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRiotGameId(): ?int
    {
        return $this->riotGameId;
    }

    public function setRiotGameId(int $riotGameId): self
    {
        $this->riotGameId = $riotGameId;

        return $this;
    }
}
