<?php


    namespace App\DataProvider;


    use ApiPlatform\Core\Bridge\Doctrine\Orm\CollectionDataProvider;
    use App\Entity\Attack;
    use App\Pokedex\AttackAPI;
    use Doctrine\Persistence\ManagerRegistry;

    class AttackCollectionProvider extends CollectionDataProvider
    {
        private AttackAPI $attackAPI;
        public function __construct(AttackAPI $attackAPI, ManagerRegistry $managerRegistry, iterable $collectionExtensions = [])
        {
            parent::__construct($managerRegistry, $collectionExtensions);
            $this->attackAPI = $attackAPI;
        }

        public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
        {
            return attack::class === $resourceClass;
        }

        public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
        {
            $this->attackAPI->getAllattacks();
            return parent::getCollection($resourceClass, $operationName, $context);
        }
    }
