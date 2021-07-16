<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LearnLevelRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=LearnLevelRepository::class)
 */
class LearnLevel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Attack::class, inversedBy="learnLevels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $attackName;

    /**
     * @ORM\ManyToOne(targetEntity=Pokemon::class, inversedBy="learnLevels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Pokemon;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttackName(): ?Attack
    {
        return $this->attackName;
    }

    public function setAttackName(?Attack $attackName): self
    {
        $this->attackName = $attackName;

        return $this;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->Pokemon;
    }

    public function setPokemon(?Pokemon $Pokemon): self
    {
        $this->Pokemon = $Pokemon;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }
}
