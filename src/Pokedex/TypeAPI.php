<?php


    namespace App\Pokedex;


    use App\Pokedex\TypeManager;
    use App\Repository\TypeRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
    use Symfony\Component\HttpClient\HttpClient;
    use Symfony\Contracts\HttpClient\HttpClientInterface;
    use App\Entity\Type;

    class TypeAPI
    {
        private HttpClientInterface $client;

        /*
         * @var TypeRepository
         */
        private TypeRepository $typeRepository;

        /*
         * @var EntityManager;
         */
        private EntityManagerInterface $em;

        public function __construct(TypeRepository $typeRepository, EntityManagerInterface $em)
        {
            $this->client = HttpClient::createForBaseUri('https://pokeapi.co/api/v2/');
            $this->typeRepository = $typeRepository;
            $this->em = $em;
        }

        public function getTypes(): array
        {
            $response = $this->client->request('GET', 'type');
            $data = $response->toArray();
            foreach ($data['results'] as $key) {
                $name = $key['name'];
                $stringExploded = explode('/', $key['url']);
                $type = ['name' => $name, 'id' => $stringExploded[6]];
                $types[] = $this->ConvertPokeapiToType($type);
//                $responsePkmn = $this->client->request('GET', 'pokemon/' . $stringExploded[6]);
            }
            return $types;
        }

        public function ConvertPokeapiToType(array $array): Type
        {
            $name = $array['name'];
            $id = $array['id'];
            $type = $this->typeRepository->findOneBy(['pokeapiId' => $id]);
            if ($type === NULL)
            {
                $type = new Type();
                $type->setName($name);
                $type->setPokeapiId($id);

                $this->em->persist($type);
                $this->em->flush();
            }
            return $type;
        }
    }
