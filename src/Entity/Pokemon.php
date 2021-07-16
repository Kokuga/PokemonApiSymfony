<?php

    namespace App\Entity;

    use ApiPlatform\Core\Annotation\ApiResource;
    use App\Repository\PokemonRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ApiResource()
     * @ORM\Entity(repositoryClass=PokemonRepository::class)
     */
    class Pokemon
    {
        /**
         * @ORM\Id
         * @ORM\GeneratedValue
         * @ORM\Column(type="integer")
         */
        private $id;

        /**
         * @ORM\Column(type="string", length=255)
         */
        private $name;

        /**
         * @ORM\Column(type="integer")
         */
        private $pokeapi_id;

        /**
         * @ORM\Column(type="integer")
         */
        private $height;

        /**
         * @ORM\Column(type="integer")
         */
        private $weight;

        /**
         * @ORM\Column(type="integer")
         */
        private $base_experience;

        /**
         * @ORM\Column(type="integer")
         */
        private $pokeapi_order;

        /**
         * @ORM\Column(type="string", length=255, nullable=true)
         */
        private $sprites;

        /**
         * @ORM\ManyToMany(targetEntity=Type::class, inversedBy="pokemon")
         */
        private $types;

        /**
         * @ORM\ManyToMany(targetEntity=Attack::class, inversedBy="pokemon")
         */
        private $attacks;

        /**
         * @ORM\OneToMany(targetEntity=LearnLevel::class, mappedBy="Pokemon")
         */
        private $learnLevels;

//    /**
//     * @ORM\ManyToMany(targetEntity=Attack::class, inversedBy="pokemon")
//     */
//    private $level_learned_at;

        public function __construct()
        {
            $this->types = new ArrayCollection();
            $this->attacks = new ArrayCollection();
//        $this->level_learned_at = new ArrayCollection();
            $this->learnLevels = new ArrayCollection();
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getName(): ?string
        {
            return $this->name;
        }

        public function setName(string $name): self
        {
            $this->name = $name;

            return $this;
        }

        public function getPokeapiId(): ?int
        {
            return $this->pokeapi_id;
        }

        public function setPokeapiId(int $pokeapi_id): self
        {
            $this->pokeapi_id = $pokeapi_id;

            return $this;
        }

        public function getHeight(): ?int
        {
            return $this->height;
        }

        public function setHeight(int $height): self
        {
            $this->height = $height;

            return $this;
        }

        public function getWeight(): ?int
        {
            return $this->weight;
        }

        public function setWeight(int $weight): self
        {
            $this->weight = $weight;

            return $this;
        }

        public function getBaseExperience(): ?int
        {
            return $this->base_experience;
        }

        public function setBaseExperience(int $base_experience): self
        {
            $this->base_experience = $base_experience;

            return $this;
        }

        public function getPokeapiOrder(): ?int
        {
            return $this->pokeapi_order;
        }

        public function setPokeapiOrder(int $pokeapi_order): self
        {
            $this->pokeapi_order = $pokeapi_order;

            return $this;
        }

        public function getSprites(): ?string
        {
            return $this->sprites;
        }

        public function setSprites(?string $sprites): self
        {
            $this->sprites = $sprites;

            return $this;
        }

        /**
         * @return Collection|Type[]
         */
        public function getTypes(): Collection
        {
            return $this->types;
        }

        public function addType(Type $type): self
        {
            if (!$this->types->contains($type)) {
                $this->types[] = $type;
            }

            return $this;
        }

        public function removeType(Type $type): self
        {
            $this->types->removeElement($type);

            return $this;
        }

        /**
         * @return Collection|Attack[]
         */
        public function getAttacks(): Collection
        {
            return $this->attacks;
        }

        public function addAttack(Attack $attack): self
        {
            if (!$this->attacks->contains($attack)) {
                $this->attacks[] = $attack;
            }

            return $this;
        }

        public function removeAttack(Attack $attack): self
        {
            $this->attacks->removeElement($attack);

            return $this;
        }

        /**
         * @return Collection|Attack[]
         */
        public function getLevelLearnedAt(): Collection
        {
            return $this->level_learned_at;
        }


        /**
         * @return Collection|LearnLevel[]
         */
        public function getLearnLevels(): Collection
        {
            return $this->learnLevels;
        }

        public function addLearnLevel(LearnLevel $learnLevel): self
        {
            if (!$this->learnLevels->contains($learnLevel)) {
                $this->learnLevels[] = $learnLevel;
                $learnLevel->setPokemon($this);
            }

            return $this;
        }

        public function removeLearnLevel(LearnLevel $learnLevel): self
        {
            if ($this->learnLevels->removeElement($learnLevel)) {
                // set the owning side to null (unless already changed)
                if ($learnLevel->getPokemon() === $this) {
                    $learnLevel->setPokemon(null);
                }
            }

            return $this;
        }
    }
