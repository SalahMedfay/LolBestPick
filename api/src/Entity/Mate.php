<?php

declare(strict_types=1);

/*
 * This file is part of a Salah Medfay project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MateRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="mate_idx", columns={"first_champion_id", "second_champion_id"})})
 */
class Mate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Champion")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $firstChampion;

    /**
     * @ORM\ManyToOne(targetEntity="Champion")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $secondChampion;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $win;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $lose;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstChampion()
    {
        return $this->firstChampion;
    }

    public function setFirstChampion($firstChampion): self
    {
        $this->firstChampion = $firstChampion;

        return $this;
    }

    public function getSecondChampion()
    {
        return $this->secondChampion;
    }

    public function setSecondChampion($secondChampion): self
    {
        $this->secondChampion = $secondChampion;

        return $this;
    }

    public function getWin(): int
    {
        return $this->win;
    }

    public function setWin(int $win): self
    {
        $this->win = $win;

        return $this;
    }

    public function getLose(): int
    {
        return $this->lose;
    }

    public function setLose(int $lose): self
    {
        $this->lose = $lose;

        return $this;
    }
}
