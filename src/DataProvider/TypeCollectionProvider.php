<?php


    namespace App\DataProvider;


    use ApiPlatform\Core\Bridge\Doctrine\Orm\CollectionDataProvider;
    use App\Pokedex\TypeAPI;
    use App\Entity\Type;
    use Doctrine\Persistence\ManagerRegistry;

    class TypeCollectionProvider extends CollectionDataProvider
    {
        private TypeAPI $typeAPI;
        public function __construct(TypeAPI $typeAPI, ManagerRegistry $managerRegistry, iterable $collectionExtensions = [])
        {
            parent::__construct($managerRegistry, $collectionExtensions);
            $this->typeAPI = $typeAPI;
        }

        public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
        {
            return Type::class === $resourceClass;
        }

        public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
        {
            $this->typeAPI->getTypes();
            return parent::getCollection($resourceClass, $operationName, $context);
        }


    }
