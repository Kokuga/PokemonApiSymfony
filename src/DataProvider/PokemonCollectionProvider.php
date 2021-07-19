<?php


    namespace App\DataProvider;


    use ApiPlatform\Core\Bridge\Doctrine\Orm\CollectionDataProvider;
    use App\Entity\Pokemon;
    use App\Pokedex\PokemonAPI;
    use Doctrine\Persistence\ManagerRegistry;

    class PokemonCollectionProvider extends CollectionDataProvider
    {
        private PokemonAPI $pokemonAPI;
        public function __construct(PokemonAPI $pokemonAPI, ManagerRegistry $managerRegistry, iterable $collectionExtensions = [])
        {
            parent::__construct($managerRegistry, $collectionExtensions);
            $this->pokemonAPI = $pokemonAPI;
        }

        public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
        {
            return Pokemon::class === $resourceClass;
        }

        public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
        {
            $this->pokemonAPI->getAllPokemons();
            return parent::getCollection($resourceClass, $operationName, $context);
        }
    }
