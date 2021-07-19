<?php


    namespace App\Pokedex;



    use App\Entity\Attack;
    use App\Repository\AttackRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpClient\HttpClient;
    use Symfony\Contracts\HttpClient\HttpClientInterface;

    class AttackAPI
    {
        private HttpClientInterface $client;

        /*
         * @var AttackRepository
         */
        private AttackRepository $AttackRepository;

        /*
         * @var EntityManager;
         */
        private EntityManagerInterface $em;

        public function __construct(AttackRepository $AttackRepository, EntityManagerInterface $em)
        {
            $this->client = HttpClient::createForBaseUri('https://pokeapi.co/api/v2/');
            $this->AttackRepository = $AttackRepository;
            $this->em = $em;
        }

        public function getAllAttacks(int $offset = 0, int $limit = 20, array $array = []): array
        {
            $response = $this->client->request('GET', 'Attack?offset=' . $offset . '&limit=' . $limit);
            $data = $response->toArray();
            $next = $data['next'];
            if ($next != NULL) {
                $limitExploded = explode('=', $next);
                $offsetExploded = explode('&', $limitExploded[1]);
            }

            $Attacks = $array;
            foreach ($data['results'] as $key) {
                $stringExploded = explode('/', $key['url']);
                $responsePkmn = $this->client->request('GET', 'attack/' . $stringExploded[6]);
                $Attack = $responsePkmn->toArray();
                $name = $Attack['name'];
                $height = $Attack['height'];
                $weight = $Attack['weight'];
                $baseExperience = $Attack['base_experience'];
                $order = $Attack['order'];
                $Attack = ['name' => $name, 'id' => $stringExploded[6], 'height' => $height, 'weight' => $weight, 'base_experience' => $baseExperience, 'order' => $order];
                $Attacks[] = $this->ConvertPokeapiToAttack($Attack);
            }


            if ($next == null) {
                return $Attack;
            } else {
                return $this->getAllAttacks($offsetExploded[0], $limitExploded[2], $Attacks);
            }
        }

        public function ConvertPokeapiToAttack(array $array): Attack
        {
            $name = $array['name'];
            $id = $array['id'];
            $order = $array['order'];
            $height = $array['height'];
            $weight = $array['weight'];
            $baseExperience = $array['base_experience'];
            $Attack = $this->AttackRepository->findOneBy(['pokedexOrder' => $order]);
            if ($Attack === NULL) {
                $Attack = new Attack($id);
                $Attack->setName($name);
                $Attack->setBaseExperience($baseExperience);
                $Attack->setWeight($weight);
                $Attack->setHeight($height);
                $Attack->setPokedexOrder($order);

                $this->em->persist($Attack);
                $this->em->flush();
            }
            return $Attack;
        }
    }
